{% extends 'base/show.html.twig' %}

{% block title %}
{{ '<?= $entity_class_name;?>'|humanize }}
{% endblock %}

{% block listTitle %}
{{ '<?= $entity_class_name;?>'|humanize }}
{% endblock %}

{% block actions %}
<a class="btn btn-primary" title="Back to the list" href="{{ path('<?= $route_name; ?>_index') }}"><i class="fa fa-list-ol fa-fw"></i></a>
<a class="btn btn-primary" title="Edit" href="{{ path('<?= $route_name; ?>_edit', { '<?= $entity_identifier; ?>': <?= $entity_var_singular; ?>.<?= $entity_identifier; ?> }) }}"><i class="fa fa-edit fa-fw"></i></a>
<button class="btn btn-danger" type="submit" value="Delete" data-require-confirm="true"><i class="fa fa-trash fa-fw"></i></button>
{% endblock %}

{% block values %}
<?php foreach ($entity_fields as $field): ?>
    <tr>
        <th><?= ucfirst($field['fieldName']); ?></th>
        <td>{{ <?= $helper->getEntityFieldPrintCode($entity_var_singular, $field); ?> }}</td>
    </tr>
<?php endforeach; ?>
{% endblock %}
