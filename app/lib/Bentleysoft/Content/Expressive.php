<?php namespace Bentleysoft\Content;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

class Expressive extends \Cartalyst\NestedSets\Nodes\EloquentNode
{
    private $media;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    /**
     * Relationship with translated content
     * One to one id=>content_id, LANG=>lang (e.g. 'el', 'zh-CN')
     *
     * @param string LANG
     *
     * @return $this
     */
    public function contentTranslated($lang = 'en')
    {
        $x = $this->hasOne('ContentTranslated', 'content_id')->where('lang', '=', $lang)->getResults();
        if ($x && $x !== null)
            return $x;

        return $this;
    }

    /**
     * Stub, needs doing
     * @author PD
     * TODO: omplement
     */
    public function getWidgetTranslated($name, $lang = 'en')
    {
        return 'WIDGETTRANSLATED';
    }

    public function getTitleTranslated($lang)
    {
        $translation = ContentTranslated::where('content_id', '=', $this->id)->where('lang', '=', $lang)->first();

        // $t = $c->contentTranslated('zh-CN')->getResults();

        if ($translation !== null)
            $title = $translation->title;
        else
            $title = $this->title;

        return $title;
    }

    public function getUrlTitle()
    {
        $res = \DB::select("select fn_urltitle('" . $this->title . "') as fn_urltitle");
        if ($res && count($res) > 0)
            return $res[0]->fn_urltitle;

        return $this->title;
    }

    /**
     *
     * @author Petros Diveris
     * @version 0.1
     * @var string mime
     */
    public function getMedia($mime = 'image')
    {
        return MediaView::where('content_id', '=', $this->id)
          ->where('mimetype', 'like', "$mime%")
          ->orderBy('tag', 'asc')
          ->orderBy('position', 'asc')
          ->get();

    }

    public function media()
    {
        $x = $this->hasMany('MediaView', 'id');

        return $x;
    }

}
