<?php
namespace App\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\WidgetInterface;
use Cake\I18n\Time;

class DateTimeWidget implements WidgetInterface
{

    protected $_templates;

    private $_cake_datetime_fields = [
        'year',
        'month',
        'day',
        'hour',
        'minute',
        'second',
        'meridian'
    ];

    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     */
    public function __construct($templates)
    {
        $this->_templates = $templates;
    }

    /**
     * Render a date or time widget.
     *
     * This method accepts a number of keys:
     *
     * - `name` The name attribute.
     * - `type` The value attribute.
     *
     * Any other keys provided in $data will be converted into HTML attributes.
     *
     * @param array $data The data to build an input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context): string
    {
        $data = $this->_normalizeData($data);

        return $this->_templates->format('dateWidget', [
            'name' => $data['name'],
            'type' => $data['type'],
            'attrs' => $this->_templates->formatAttributes($data, ['name'])
        ]);
    }

    /**
     * Returns a list of fields that need to be secured for
     * this widget.
     *
     * @param array $data The data to render.
     * @return array Array of fields to secure.
     */
    public function secureFields(array $data): array
    {
        return [$data['name']];
    }

    /**
     * Normalize data.
     *
     * @param array $data Data to normalize.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return array Normalized data.
     */
    private function _normalizeData(array $data)
    {
        $val = isset($data['val']) && $data['val'] != ''? $data['val']: '';
        unset($data['val']);

        if ($val != '') {
            if (is_string($val)) {
                $val = new Time($val);
            }
            if ($data['type'] == 'date') {
                $data['value'] = $val->i18nFormat('yyyy-MM-dd');
            } elseif ($data['type'] == 'time') {
                $data['value'] = $val->i18nFormat('HH:mm');
            }
        }

        foreach ($this->_cake_datetime_fields as $field) {
            unset($data[$field]);
        }

        return $data;
    }
}
