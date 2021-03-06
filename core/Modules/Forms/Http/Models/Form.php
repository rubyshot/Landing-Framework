<?php
namespace Modules\Forms\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model {

  protected $table = 'forms';

  protected $casts = [
    'meta' => 'json'
  ];

  public function user() {
    return $this->belongsTo('App\User');
  }

  public function funnel() {
    return $this->belongsTo('Platform\Models\Funnels\Funnel');
  }

  public function emails() {
    return $this->belongsToMany('Modules\EmailCampaigns\Http\Models\Email', 'email_forms', 'form_id', 'email_id');
  }

  /**
   * Get form url.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeUrl($query) {

    $local_domain = 'f/' . $this->local_domain;

    if ($this->domain == '') {
      return url($local_domain);
    } else {
      return 'http://' . $this->domain;
    }
  }
}