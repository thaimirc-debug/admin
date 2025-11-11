<div>
    <div class="mx-auto mb-3" style="width:335px">
        <div class="card shadow">
            <div class="card-header">
                <h4>ลงชื่อคุณ{{ $customer->name }}</h4>
            </div>
            <div class="card-body">
                <canvas id="signatureCanvas" width="300" height="120"
                    style="border:1px solid #ccc; touch-action:none;"></canvas>
                <a class="btn btn-outline-secondary btn-sm" onclick="undo()">Undo</a>
                <a class="btn btn-outline-blue btn-sm" onclick="redo()">Redo</a>
                <a class="btn btn-outline-danger btn-sm" onclick="clearCanvas()">Clear</a>
                <button type="button" wire:click="deleteSignature" class="btn btn-danger">
                    ลบลายเซ็นต์เก่า
                </button>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-2">
                        <input type="color" id="penColor" value="#020202"
                            class="text-start form-control-sm p-0 border-0" />

                    </div>
                    <div class="col-10 text-end">
                        <a class="btn btn-outline-pink btn-sm"
                            href="{{ url('customer/' . $customer->id . '/plan') }}"><i
                                class="fa-solid fa-arrow-rotate-left"></i> ยกเลิก</a>

                        <button class="btn btn-blue btn-sm" onclick="saveSignature()"><i
                                class="fa-solid fa-fingerprint"></i> บันทึกลายมือ</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




<script>
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const penColorInput = document.getElementById('penColor');
    let drawing = false;
    let lastPos = { x: 0, y: 0 };
    let currentColor = penColorInput.value;
    let strokes = [];
    let undone = [];

    penColorInput.addEventListener('input', () => {
        currentColor = penColorInput.value;
    });

    canvas.addEventListener('pointerdown', e => {
        drawing = true;
        lastPos = getCanvasPos(e);
        ctx.beginPath();
        ctx.moveTo(lastPos.x, lastPos.y);

        strokes.push({
            color: currentColor,
            width: e.pressure * 6 || 2.5, // เพิ่มความหนาของเส้น เดิม *5 เพิ่มเป็น *6 หรือ *8 ตามต้องการ
            points: [lastPos],
        });

        undone = []; // reset redo history
    });

    canvas.addEventListener('pointerup', endStroke);
    canvas.addEventListener('pointerout', endStroke);

    function endStroke() {
        drawing = false;
        ctx.beginPath();
    }

    canvas.addEventListener('pointermove', e => {
        if (!drawing) return;
        const pos = getCanvasPos(e);
        const pressure = e.pressure || 0.5;
        const width = pressure * 6; // // เพิ่มความหนาของเส้น เปลี่ยนตรงนี้ด้วยให้ตรงกัน

        const stroke = strokes[strokes.length - 1];
        stroke.points.push(pos);
        stroke.width = width;

        drawAll();
    });

    function drawAll() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let stroke of strokes) {
            ctx.strokeStyle = stroke.color;
            ctx.lineWidth = stroke.width;
            ctx.lineCap = 'round';

            ctx.beginPath();
            for (let i = 1; i < stroke.points.length; i++) {
                const midPoint = {
                    x: (stroke.points[i - 1].x + stroke.points[i].x) / 2,
                    y: (stroke.points[i - 1].y + stroke.points[i].y) / 2
                };
                ctx.quadraticCurveTo(stroke.points[i - 1].x, stroke.points[i - 1].y, midPoint.x, midPoint.y);
            }
            ctx.stroke();
        }
    }

    function undo() {
        if (strokes.length > 0) {
            undone.push(strokes.pop());
            drawAll();
        }
    }

    function redo() {
        if (undone.length > 0) {
            strokes.push(undone.pop());
            drawAll();
        }
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        strokes = [];
        undone = [];
        Livewire.find('{{ $this->getId() }}').set('signatureData', '');
    }

    function getResizedDataUrl(width = 80) {
        const scale = width / canvas.width;
        const height = canvas.height * scale;

        const resizedCanvas = document.createElement('canvas');
        resizedCanvas.width = width;
        resizedCanvas.height = height;

        const resizedCtx = resizedCanvas.getContext('2d');
        resizedCtx.drawImage(canvas, 0, 0, width, height);

        return resizedCanvas.toDataURL('image/webp', 1.0);
    }

    function saveSignature() {
        if (strokes.length === 0) {
            alert('กรุณาเซ็นก่อนบันทึก');
            return;
        }

        const resizedDataUrl = getResizedDataUrl(150); // resize กว้าง 150px
        Livewire.find('{{ $this->getId() }}').set('signatureData', resizedDataUrl);
        Livewire.find('{{ $this->getId() }}').call('save');
    }

    function getCanvasPos(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }
</script>