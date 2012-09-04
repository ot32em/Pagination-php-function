@name Pagination
@description Generator a pagination bar in html-format.

@author Ot Chen
@email ot32em@gmail.com
@date 2012.09.04

@output-example
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

@param $num_allitems    integer, Number of whole items.
@param $cur_page        integer, Current page number.
@param $page_size       integer, How many items in a page.
@param $link_format     string, 
    A simple string format with two '%s' symbols for sprintf built-in 
    method to  replace first %s to variable name of page number in url,
    and replace second %s to value of page.
       For a example. given var_name is p and page value is 34.
           with the format /%s/%s, it will be /p/34
           with the format ?%s=%s, it will be ?p=34
           with the format /thread/view/%s/%s, 
                it will be /thread/view/p/34. full length url may like http://yourhost.com/thread/view/p/34, and you can see the 
                comment of thread in the 34th page.
       By default, it is "?%s=%s"

@param $args            key-value array, 
    For optional setting where key is config name, and description as following
    @config-name var_name       string
        The variable name of page showing in the link. By Default it is 'p'.
        
    @config-name label_previous string 
        A custom label on the previous button. By Default it is 'Previous'.
        
    @config-name label_next     string, 
        A custom label on the next button. By Default it is 'Next'.
        
    @config-name max_section    int 
        How many page buttons in the given range. By Default it is 10.
        
    @config-name css_prefix     string 
        Add prefix to each name of css class for avoiding name collision. 
        By Default it is 'paging_'

