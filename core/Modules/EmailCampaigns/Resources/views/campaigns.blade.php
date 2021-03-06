<div class="container">
 
  <div class="row m-t">
    <div class="col-sm-12">
     
       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('emailcampaigns::global.module_name_plural') }} ({{ count($email_campaigns) }})</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">

                <div class="input-group input-group" style="margin:0 5px 0 0">
                  <span class="input-group-addon" onClick="if ($('#grid_search:visible').length) { $('#grid_search').delay().animate({width:'0px'}, 150, '').hide(0); } else { $('#grid_search').show().animate({width:'180px'}, 500, 'easeOutBounce'); }"><i class="mi search"></i></span>
                  <input type="text" class="form-control input" id="grid_search" placeholder="{{ trans('global.search_') }}" style="width:0px;display: none">
                </div>

                <div class="input-group input-group" style="margin:0 5px 0 0">
                  <span class="input-group-addon" onClick="if ($('#order_selector:visible').length) { $('#order_selector').delay().animate({width:'0px'}, 150, '').hide(0); } else { $('#order_selector').show().animate({width:'180px'}, 500, 'easeOutBounce'); }"><i class="mi sort"></i></span>
                  <div style="width: 0; overflow: hidden; display: none" id="order_selector">
                  <div style="min-width:180px">
                    <select id="order" class="select2-required-no-search">
                      <option value="new_first"<?php if ($order == 'new_first') echo ' selected'; ?>>{{ trans('global.new_first') }}</option>
                      <option value="old_first"<?php if ($order == 'old_first') echo ' selected'; ?>>{{ trans('global.old_first') }}</option>
                    </select>
                  </div>
                  </div>
                </div>
<script>
$('#order').on('change', function() {
  document.location = '#/emailcampaigns/order/' + $(this).val();
});
</script>
                <a href="#/emailcampaigns/create" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('emailcampaigns::global.create_campaign') }}</a>
            </div>

          </div>
        </div>
      </nav>
    
    </div>
  </div>

  <div class="row grid" id="grid">
    <div class="grid-sizer col-xs-6 col-sm-3 col-lg-3" style="display:none"></div>
<?php 
$i = 1;
foreach($email_campaigns as $campaign) {
  $sl_campaign = \Platform\Controllers\Core\Secure::array2string(['email_campaign_id' => $campaign->id]);

  $emails = \Modules\EmailCampaigns\Http\Models\Email::where('email_campaign_id', $campaign->id)->orderBy('created_at', 'desc')->get();
  $email_count = $emails->count();
?>
    <div class="grid-item col-xs-6 col-sm-3 col-lg-3" id="item{{ $i }}">

      <div class="grid-item-content portlet shadow-box" data-sl="{{ $sl_campaign }}">

        <div class="btn-group pull-right">
          <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mi more_vert"></i>
          </button>
          <ul class="dropdown-menu m-t-0">
            <li><a href="#/emailcampaigns/edit/{{ $sl_campaign }}">{{ trans('emailcampaigns::global.edit_email_campaign') }}</a></li>
            <li><a href="#/emailcampaigns/emails/{{ $sl_campaign }}">{{ trans('emailcampaigns::global.manage_emails') }}</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="javascript:void(0);" class="onClickDelete">{{ trans('global.delete') }}</a></li>
          </ul>
        </div>

        <div class="portlet-heading portlet-default">
          <h3 class="portlet-title text-dark" title="{{ $campaign['name'] }}">{{ trans_choice('emailcampaigns::global.' . $campaign->type, $email_count) }}</h3>
          <div class="clearfix"></div>
        </div>

        <div class="portlet-body" style="padding:0">
         <table class="table" style="margin-bottom: 0">
           <tr>
             <td width="60" class="text-center" style="padding:0"><a href="#/emailcampaigns/emails/{{ $sl_campaign }}"><img src="{{ url('assets/images/icons/color/' . $categories[$campaign->type]['icon']) }}" style="height:24px;margin: 10px 0"></a></td>
             <td style="vertical-align: middle"><a href="#/emailcampaigns/emails/{{ $sl_campaign }}" class="link filter-this">{{ $campaign['name'] }}</a> ({{ number_format($email_count) }})</td>
           </tr>
<?php if ($email_count > 0) { ?>
           <tr>
             <td colspan="2" class="list-group-container">
               <div class="list-group" style="margin-bottom: 0;">
<?php

foreach ($emails as $email) {
  $sl_email = \Platform\Controllers\Core\Secure::array2string(['email_id' => $email->id]);
?>
                 <a href="#/emailcampaigns/emails/editor/{{ $sl_email }}" class="filter-this list-group-item" style="border-right:0;border-left:0">{{ $email->name }}</a>
<?php
}
?>
               </div>
             </td>
           </tr>
<?php } ?>

<?php /*
           <tr>
             <td width="33" class="text-center"><i class="mi open_in_browser"></i></td>
             <td>{{ trans('global.opens') }}:</td>
             <td class="text-right"><strong>{{ number_format($campaign->opens) }}</strong></td>
           </tr>
           <tr>
             <td class="text-center"><i class="mi touch_app"></i></td>
             <td>{{ trans('global.clicks') }}:</td>
             <td class="text-right"><strong>{{ number_format($campaign->clicks) }}</strong></td>
           </tr>*/ ?>
         </table>
        </div>
<?php /*
        <div>
          <a href="#/emailcampaigns/emails/{{ $sl_campaign }}" class="preview-container" id="container{{ $i }}" title="{{ $campaign['name'] }}">
            <img src="{{ url('assets/images/icons/color/' . $categories[$campaign->type]['icon']) }}">
          </a>
        </div>
*/ ?>
      </div>

    </div>
<?php 
  $i++;
} 
?>
  </div>
</div>

<style type="text/css">
.list-group-container {
  padding: 0 !important;
}
.
td.list-group-container .list-group-item {
  border: 0 !important;
}

.preview-container {
  text-align: center;
  display: block;
}
.preview-container img {
  height: 64px;
  margin: 30px 10px;
}

.portlet-title {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}
</style>

<script>
$(function() {
  var $grid = $('.grid').masonry({
    itemSelector: '.grid-item',
    columnWidth: '.grid-sizer',
    percentPosition: true,
    transitionDuration: '0.2s'
  });

  $('#grid').liveFilter('#grid_search', 'div.grid-item', {
    filterChildSelector: '.filter-this',
    after: function() {
      $grid.masonry();
    }
  });


$('.onClickDelete').on('click', function() {
  var sl = $(this).parents('.grid-item-content').attr('data-sl');
  var $item = $(this).parents('.grid-item');

  swal({
    title: _lang['delete'],
    text: _lang['confirm'],
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#da4429",
    confirmButtonText: _lang['yes_delete']
  }).then(function (result) {

    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('emailcampaigns/delete') }}",
      data: {sl: sl,  _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      $item.remove();
      $grid.masonry();
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
     unblockUI();
    });

  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
});
});
</script>