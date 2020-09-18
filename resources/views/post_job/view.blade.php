<!-- Modal -->
<div class="modal fade" id="jobViewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td width="25%">Title</td>
                        <td width="25%" id="title"></td>
                        <td width="25%">Salary</td>
                        <td width="25%" id="salary"></td>
                    </tr>
                    <tr>
                        <td width="25%">Location</td>
                        <td width="25%" id="location"></td>
                        <td width="25%">Country</td>
                        <td width="25%" id="country"></td>
                    </tr>
                    <tr>
                        <td width="25%">Description</td>
                        <td width="75%" colspan="3" id="description"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>