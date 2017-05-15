<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
class PageAce extends Page{

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    public function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }
    /**
     * 组装分页链接
     * @return string
     */
    public function show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        $up_page = $up_row > 0 ? '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';
        $up_page = $this->form_a($up_page);

        //下一页
        $down_row  = $this->nowPage + 1;
        $down_page = ($down_row <= $this->totalPages) ? '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';
        $down_page = $this->form_a($down_page);

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = '<a class="first" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
        }
        $the_first = $this->form_a($the_first);

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = '<a class="end" href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
        }
        $the_end = $this->form_a($the_end);

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                	//$link_page .= '<a href="' . $this->url($page) . '">' . $page . '</a>';
                    $link_page .= $this->form_a( '<a href="' . $this->url($page) . '">' . $page . '</a>' );
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                	//$link_page .= '<span class="current">' . $page . '</span>';
                    $link_page .= $this->form_span( '<span class="current">' . $page . '</span>' );
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);

        return '<ul class="pagination">'."{$page_str}</ul>";
    }

    function form_a( $a_str ){
    	if ( !$a_str ) return $a_str;
    	 
    	$a_str = '<li class="paginate_button " tabindex="0" aria-controls="sample-table-2">'. $a_str. '</li>';
    	return $a_str;
    }
    
    function form_span( $a_str ){
    	if ( !$a_str ) return $a_str;
    	
    	$a_str = '<li class="paginate_button active" tabindex="0" aria-controls="sample-table-2">'. $a_str. '</li>';
    	return $a_str;
    }
}
