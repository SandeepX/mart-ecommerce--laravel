<div class="panel panel-info">
      <div class="panel-heading">
            <h3 id="report_title" class="panel-title">
                Last Sync Date
            </h3>
          <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
              <a target="_blank" href="{{ route('admin.wh-dispatch-sync-logs.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                  <i class="fa fa-eye"></i>
                  Dispatch Status Log
              </a>
          </div>
      </div>
      <div class="panel-body">
          <div class="col-md-6">
                Last Sync Normal Order Date : {{$lastDispatchSyncData['normalOrder']['date']}}<br/>
                Status :  {{$lastDispatchSyncData['normalOrder']['status']}} <br/>
                Order Count :  {{$lastDispatchSyncData['normalOrder']['count']}}
          </div>
          <div class="col-md-6">
                Last Sync PreOrder Date : {{$lastDispatchSyncData['preOrder']['date']}} <br/>
                Status :  {{$lastDispatchSyncData['preOrder']['status']}}<br/>
                Pre Order Count:  {{$lastDispatchSyncData['preOrder']['count']}}
          </div>
      </div>
</div>

