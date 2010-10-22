<?php
/**
 * Copyright (C) 2010  Moodlerooms Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @copyright  Copyright (c) 2009 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license    http://opensource.org/licenses/gpl-3.0.html     GNU Public License
 */
 
/**
 * A report for viewing the gapps actions for debugging and testing purposes
 *
 * @package block_gapps
 * @author Chris Stones
 */
class blocks_gapps_report_gappslogs extends mr_report_abstract {

    /**
     * Report's init routine
     *
     * @return void
     */
    public function init() {
        $this->config->set(array(
            'export' => '**',
            'perpage' => true,
            'perpageopts' => array('all', 1, 5, 10, 50,100),
        ));
    }

    /**
     * The component string, used for get_string() calls
     *
     * @return string
     */
    public function get_component() {
        return 'block_gapps';
    }

    /**
     * Table setup
     *
     * @return void
     */
    public function table_init() {
        $this->url->params(array('controller' => 'gsync', 'action' => 'gappslogs'));
        $this->table = new mr_html_table($this->preferences, $this->url, 'time');
        $this->table->add_column('id',     get_string('id',    'block_gapps'))
                    ->add_column('time',   get_string('time',  'block_gapps'))
                    ->add_column('userid', get_string('userid','block_gapps'))
                    ->add_column('ip',     get_string('ip',    'block_gapps'))
                    ->add_column('course', get_string('course','block_gapps'))
                    //->add_column('module', get_string('module','block_gapps'))
                    ->add_column('cmid',   get_string('cmid',  'block_gapps'))
                    ->add_column('action', get_string('action','block_gapps'))
                    //->add_column('url',    get_string('url',   'block_gapps'))
                    ->add_column('info',   get_string('info',  'block_gapps'));
    }



    /**
     * A hook into the rendering of the table.
     *
     * If you need to wrap the table in a form or anything
     * like that, then use this method.
     *
     * @param string $tablehtml The rendered table HTML
     * @return string
     */
    public function output_wrapper($tablehtml) {
        $head = '<span class="widereportbox">';
        $tail = '</span>';
        return $head.$tablehtml.$tail;
    }

    
    /**
     * Add a row to the table
     *
     * @param mixed $row The row to add
     * @return void
     */
    public function table_fill_row($row) {
        $row->time = userdate($row->time);
        $this->table->add_row($row);
    }


    /**
     * Filter setup
     *
     * @return void
     */
    public function filter_init() {
        $this->filter = new mr_html_filter($this->preferences, $this->url);
        $this->filter->new_text('info', 'Info');
        $this->filter->new_text('course', 'course');
        $this->filter->new_daterange('time', 'Times');
    }


    /**
     * Pull logs only from block_gapps actions
     */
    public function get_sql($fields, $filtersql, $filterparams) {
        global $CFG,$SESSION;

        $sql = '';

        $select = "SELECT ".$fields;
        $from   = "FROM {log}";
        $where  = "WHERE module='block_gapps' ";

        // if filter sql exists..
        if (!empty($filtersql)) {
            $where .= " AND $filtersql";
        }

        $sql = $select.' '.$from.' '.$where;
        return array($sql,$filterparams);
    }

}
