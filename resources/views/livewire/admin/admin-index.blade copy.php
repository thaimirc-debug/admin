<div>
    <div id="summernote"></div>
</div>

@push('navbar')
<div class="container mt-3">
    <div class="text-end">
        <h3 type="button" data-bs-toggle="collapse" data-bs-target="#demo"><i class="fa-solid fa-bars"></i></h3>
    </div>
  <div id="demo" class="collapse show">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div>
  <div style="border-top:3px solid #ec17e9; ">
    asasasas
  </div>
</div>

@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
      $('#summernote').summernote({
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 520,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ],
        clipboard: {
            // Match default clipboard clearData API
            matchVisual: false, // Try to keep visual structure of pasted content
        },
        // หรือลองใช้ filterPaste:
        filterPaste: true,
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault(); // ป้องกันการ Paste แบบ Default

                // ทำการ Insert Text อย่างเดียว
                document.execCommand('insertText', false, bufferText);

                // หรือถ้าต้องการจัดการ HTML เอง (อาจซับซ้อนกว่า):
                // var bufferHtml = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                // // ทำการ Filter HTML ที่ได้ แล้ว Insert
                // var cleanedHtml = filterDivs(bufferHtml);
                // document.execCommand('insertHTML', false, cleanedHtml);
            }
        },
      });
      
      function filterDivs(html) {
        // Regular expression เพื่อลบ tag <div> ทั้งหมด (รวม tag เปิดและปิด)
        var regex = /<div[^>]*>|<\/div>/gi;
        return html.replace(regex, '');
      }

    </script>
@endpush