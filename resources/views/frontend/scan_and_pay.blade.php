@extends('frontend.layouts.app')

@section('title', 'QR Scan and Pay')

@section('content')
<div class="scan-and-pay">
    <div class="card my-card">
        <div class="card-body text-center">
            <div class="text-center">
                <img src="{{ asset('images/scan-and-pay.png')}}" style="width: 250px" alt="">
                <p class="mb-1">Click button,put QR code in the frame and pay</p><br>
            </div>    
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#scanModal">
              Scan
            </button>
  
          <!-- Modal -->
          <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="scanModalLabel">Scan And Pay</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <video id="scanner"></video>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

@endsection

@section('scripts') 
<script src="{{ asset('frontend/js/qr-scanner.umd.min.js') }}"></script>
<script>
    $(document).ready(function() {
      var videoElem = document.getElementById('scanner');
      const qrScanner = new QrScanner(videoElem, function(result) {
        console.log(result);
      });

      $('#scanModal').on('shown.bs.modal', function (event) {
        qrScanner.start();
      });

      $('#scanModal').on('hidden.bs.modal', function (event) {
        qrScanner.stop();
      });
    });
</script>
@endsection
