<?php

return array(
    /**
     * field_type contains field_type value and default column_type pair
     *
     * @type array
     */
    'fieldTypes' => array(
        array('input-text', 'string'),
        array('rich-text', 'text'),
        array('image', 'string'),
//        array('inline-image', 'string'),
        array('boolean', 'smallInteger'),
        array('calendar', 'timestamp'),
        array('one-to-many', 'integer'),
        array('many-to-many', 'disabled'),
        array('weight', 'integer'),
        array('hidden', 'string')
    ),
    /**
     * Available column types (compatible with Laravel Schema builder http://four.laravel.com/docs/schema#adding-columns)
     *
     * @type array
     */
    'columnTypes' => array(
        'string',
        'integer',
        'text',
        'smallInteger',
        'bigInteger',
        'float',
        'boolean',
        'date',
        'dateTime',
        'time',
        'timestamp',
        'binary'
    )
);
