{% extends 'base/index.html.twig' %}

{% block title %}
{{ '<?= $entity_var_plural;?>'|humanize }}
{% endblock %}

{% block listTitle %}
{{ '<?= $entity_var_plural;?>'|humanize }}
{% endblock %}

{% block thead %}
    <?php foreach ($entity_fields as $field): ?><th><?= ucfirst($field['fieldName']); ?></th>
    <?php endforeach; ?><th class="text-right">Actions</th>
{% endblock %}

{% block tbody %}
    {% for <?= $entity_var_singular; ?> in <?= $entity_var_plural?> %}
        <tr>
            <?php foreach ($entity_fields as $field): ?><td>{{ <?= $helper->getEntityFieldPrintCode($entity_var_singular, $field); ?> }}</td>
            <?php endforeach; ?><td>
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a class="btn btn-secondary" href="{{ path('<?= $route_name; ?>_show', {'<?= $entity_identifier; ?>':<?= $entity_var_singular; ?>.<?= $entity_identifier; ?>}) }}"><i class="fas fa-eye"></i></a>
                    <a class="btn btn-secondary" href="{{ path('<?= $route_name; ?>_edit', {'<?= $entity_identifier; ?>':<?= $entity_var_singular; ?>.<?= $entity_identifier; ?>}) }}"><i class="fas fa-edit"></i></a>
                </div>
            </td>
        </tr>

    {% else %}
        <tr>
            <td colspan="<?= (count($entity_fields) + 1); ?>">no records found</td>
        </tr>
    {% endfor %}
{% endblock %}

{% block top_right_buttons %}
    <a class="btn btn-sm btn-primary float-right" href="{{ path('<?= $route_name; ?>_new') }}"><i class="fa fa-plus fa-fw"></i>&nbsp;Add <?= $entity_var_singular ?></a>
{% endblock %}
