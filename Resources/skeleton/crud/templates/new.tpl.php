{% extends 'base/new.html.twig' %}

{% block title %}
{{ '<?= $entity_class_name;?>'|humanize }}
{% endblock %}

{% block listTitle %}
{{ '<?= $entity_class_name;?>'|humanize }}
{% endblock %}

{% block actions %}
    <a class="btn btn-primary" title="Back to the list" href="{{ path('<?= $route_name; ?>_index') }}"><i class="fa fa-list-ol fa-fw"></i></a>
{% endblock %}
