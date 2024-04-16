<!-- Modal Content -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modal-default"
     aria-hidden="true">
    <div class="modal-dialog modal-primary modal-dialog-centered" role="document">
        <div class="modal-content bg-gradient-secondary">
            <form action="" id="formDeleteItem"
                  method="post">
                <div class="modal-body">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body bottom-0 text-white">
                        <div class="py-0 text-center" style="text-align: center">

                            <div class="d-flex justify-content-center bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
                                     <span class="modal-icon" style="text-align: center">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm iconAlarm text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
</svg>
                                 </span>
                                </div>

                            </div>
                            <h2 class="h4 modal-title my-3">Xác nhận xoá item</h2>
                            <p></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-danger btn-block">Xác nhận</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary btn-block" id="hideModalDelete"
                                            data-bs-dismiss="modal">Huỷ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- End of Modal Content -->
