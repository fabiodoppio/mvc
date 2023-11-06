<ul class="list">
    {% foreach ($items as $item): %}
        {% include admin/elements/AccountListItem.tpl %}
    {% endforeach; %}
</ul>
<ul class="pagination">
    {% if ($page > 1): %}<li class="previous"><a data-request="admin/account/scroll" data-value="{{$page-1}}">{{$page-1}}</a></li>{% endif; %}
    <li class="current">{{$page}}</li>
    {% if ($page < $pages): %}<li class="next"><a data-request="admin/account/scroll" data-value="{{$page+1}}">{{$page+1}}</a></li>{% endif; %}
</ul>