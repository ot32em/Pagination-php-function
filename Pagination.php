<?php
    /*
     * @description Generator a pagination bar in html-format.
     * @output-example
        <div class="pagination">
            <span class="step disable">Previous</span>
            <span class="number current">1</span>
            <span class="number"><a href="/thread/view/32/p/2">2</a></span>
            <span class="number"><a href="/thread/view/32/p/3">3</a></span>
            <span class="number"><a href="/thread/view/32/p/4">4</a></span>
            <span class="number"><a href="/thread/view/32/p/5">5</a></span>
            <span class="ellipsis">&#8230;</span>
            <span class="number"><a href="/thread/view/32/p/32">32</a></span>
            <span class="number"><a href="/thread/view/32/p/33">33</a></span>
            <span class="step"><a href="/thread/view/32/p/2"> Next </a></span>
        </div>
     *
     * @param $num_allitems   integer
     * @param $cur_page         integer
     * @param $page_size        integer
     * @param $link_format    string, a simple string format with two '%s' symbols for sprintf built-in method to replace first %s to variable name of page
     *   number in url, and replace second %s to value of page.
     *   For a example. given var_name is p and page value is 34.
     *      with the format /%s/%s, it will be /p/34
     *      with the format ?%s=%s, it will be ?p=34
     *      with the format /thread/view/%s/%s, it will be /thread/view/p/34. full length url may like http://yourhost.com/thread/view/p/34, and you can see the comment of thread in the 34th page.
     *  By default, it is "?%s=%s", you must give the correct format for what you want to present.
     *
     *  @param $args key-value array for optional setting
     *      where key is config name, and description as following
     *      @config-name var_name            string, The variable name of page showing in the link.. By Default it is 'p'
     *      @config-name label_previous     string, A custom label on the previous button. By Default it is 'Previous'
     *      @config-name label_next            string, A custom label on the next button, By Default it is 'Next'
     *      @config-name max_section         int, How many page buttons in the given range. By Default it is 10
     *      @config-name css_prefix            string, Add prefix to each name of css class for avoiding name collision. By Default it is 'paging_'
     *
     *
     * @author ot32em@gmail.com
     * @date 2012.09.04
     */
function pagination_html( $num_allitems=0, $cur_page=1, $page_size=10, $link_format = '?%s=%s', $args = array())
{
    /* config variables */
    $var_name = array_key_exists('var_name', $args) ? $args['var_name'] :
        'p';
    $link_format = sprintf( $link_format, $var_name, '%s'); // replace %s to given var_name

    $label_previous = array_key_exists('label_previous', $args ) ? $args['label_previous'] :
        'Previous';
    $label_next = array_key_exists('label_next', $args ) ? $args['label_next'] :
        'Next';
    $max_section = array_key_exists('max_section', $args) ? $args['max_section'] :
        10; //  previous n-4 n-3 n-2 n-1 n n+1 n+2 n+3 n+4 n+5 ... last-1 last next

    $css_prefix = array_key_exists('css_prefix', $args) ? $args['css_prefix'] :
        'paging_';
    /* end of setting config variables */

    /* calculate initial variable */
    if( $num_allitems == 0)
        $num_allpages = 1;
    else
        $num_allpages = floor( ( $num_allitems - 1 ) / $page_size ) + 1;
    /* end of calculating initial variables */

    /* processing steps buttons which are previous and next button */
    // previous button
    $html_previous = '';
    if( $cur_page <= 1){
        $html_css_disable = "{$css_prefix}disable";
        $html_anchor = '';
        $html_anchor_close = '';
    }else{
        $html_css_disable = '';
        $page_val = $cur_page - 1;
        $link = sprintf( $link_format, $page_val );
        $html_anchor = "<a href=\"{$link}\">";
        $html_anchor_close = '';
    }
    $html_previous .= "<span class=\"{$css_prefix}step {$html_css_disable}\">".$html_anchor.$label_previous.$html_anchor_close."</span>\n";

    // next button
    $html_next = '';
    if( $cur_page >= $num_allpages){
        $html_css_disable = 'disable';
        $html_anchor = '';
        $html_anchor_close = '';
    }else{
        $html_css_disable = '';
        $page_val = $cur_page + 1;
        $link = sprintf( $link_format, $page_val );
        $html_anchor = "<a href=\"{$link}\">";
        $html_anchor_close = "</a>";
    }
    $html_next .= "<span class=\"{$css_prefix}step {$html_css_disable}\">".$html_anchor.$label_next.$html_anchor_close."</span>\n";
    /* end of processing steps buttons */


    /* process buttons which are BEFORE and AFTER current button */

    // for example, if max_secion = 10, and current page is 5,
    // then buttons before current page could be 1 ~ 4 in 4 of count and
    //  buttons after current page could be 6 - 10 in 5 of count.
    // so, "before" uses =>  floor like 10-1 = 9, floor( 9 /2 ) = 4
    //  and "after" uses => ceil like ceil( 9/2) = 5 and matches what I want.

    $before_start_number = max( 1, $cur_page - floor(
        ($max_section - 1 ) / 2
    )
    ); // in example it is 1

    $before_end_number = max( 1, $cur_page - 1); // in example it is 4

    $after_start_number = min( $num_allpages, $cur_page + 1 ); // in example, it is 6

    $after_end_number = min( $num_allpages, $cur_page + ceil(
        ($max_section - 1 ) / 2
    )
    );

    $html_before = '';
    for( $i = $before_start_number ; $i <= $before_end_number && $i < $cur_page; $i++)
        // with $i < $cur_page condition to avoid that situation you get current-page = 1, and before-min and before-max are also page 1 to page 1
        // that will duplicate a current page button next to it and to be a unwanted result.
    {
        $page_val = $i;
        $link = sprintf( $link_format, $page_val );
        $html_before .= "<span class=\"{$css_prefix}number\"><a href=\"{$link}\">$page_val</a></span>\n";
    }

    $html_after = '';
    for( $i = $after_start_number ; $i <= $after_end_number && $i > $cur_page ; $i++)
    {
        $page_val = $i;
        $link = sprintf( $link_format, $page_val );
        $html_after .= "<span class=\"{$css_prefix}number\"><a href=\"{$link}\">$page_val</a></span>\n";
    }
    /* end of processing BEFORE and AFTER buttons */


    /* processing ellipsis symbol */
    // 5 6 7 8 9 10 ... 10 --- don't need ellipsis
    // 5 6 7 8 9 10 ... 11 --- need ellipsis dif is >= 1
    // 5 6 7 8 9 10 ... 11 12 --- need ellipsis dif is >= 2
    // 5 6 7 8 9 10 ... 12 13 --- need dif is >= 3
    // $num_allpages - after_end_number >= 3 is the condition
    $html_last_ellipsis = '';
    if( $num_allpages - $after_end_number >= 1 )
        $html_last_ellipsis = "<span class=\"{$css_prefix}ellipsis\">&#8230;</span>\n"; // &#8230; is the ellipsis symbol in html code like "..."

    $html_first_ellipsis = '';
    if( $before_start_number - 1 >= 1 )
        $html_first_ellipsis = "<span class=\"{$css_prefix}ellipsis\">&#8230;</span>\n"; // &#8230; is the ellipsis symbol in html code like "..."
    /* end of processing ellipsis symbol */

    /* processing first and last buttons */
    //  5 6 7 8 9 10 , 11 last has only 11
    //  5 6 7 8 9 10 , 11 12 last has 11 and 12
    $html_last = '';
    $last_start_number = max( $after_end_number, $num_allpages - 1);
    $last_end_number = $num_allpages;
    $fix_reverse_order_html_last_ary = array();
    for( $i = $last_end_number ; $i >= $last_start_number AND $i > $after_end_number; $i--)
    {
        $page_val = $i;
        $link = sprintf( $link_format, $page_val);
        // $html_last .= "<span class=\"{$css_prefix}last\"><a href=\"{$link}\">{$page_val}</a></span>\n"; need reverse order
        $fix_reverse_order_html_last_ary[] = "<span class=\"{$css_prefix}last\"><a href=\"{$link}\">{$page_val}</a></span>\n";
    };
    $correct_order_html_last_ary = array_reverse( $fix_reverse_order_html_last_ary);
    $html_last = implode('', $correct_order_html_last_ary);

    $html_first = '';
    $first_start_number = 1;
    $first_end_number = min( $before_start_number, 2);
    for( $i = $first_start_number ; $i <= $first_end_number AND $i < $before_start_number; $i++)
    {
        $page_val = $i;
        $link = sprintf( $link_format, $page_val);
        $html_first .= "<span class=\"{$css_prefix}first\"><a href=\"{$link}\">{$page_val}</a></span>\n";
    };
    /* end of processing last buttons */

    /* processing current button */
    $page_val = $cur_page;
    $link = sprintf($link_format, $page_val);
    $html_current = "<span class=\"{$css_prefix}number {$css_prefix}current\">{$page_val}</span>\n";
    /* end of current button */

    /* assemble all parts into finaly result */
    $html = $html_previous.
            $html_first.
            $html_first_ellipsis.
            $html_before.
            $html_current.
            $html_after.
            $html_last_ellipsis.
            $html_last.
            $html_next;

    return $html;
}
    