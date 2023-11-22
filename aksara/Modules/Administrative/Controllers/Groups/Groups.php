<?php

/**
 * This file is part of Aksara CMS, both framework and publishing
 * platform.
 *
 * @author     Aby Dahana <abydahana@gmail.com>
 * @copyright  (c) Aksara Laboratory <https://aksaracms.com>
 * @license    MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.txt file.
 *
 * When the signs is coming, those who don't believe at "that time"
 * have only two choices, commit suicide or become brutal.
 */

namespace Aksara\Modules\Administrative\Controllers\Groups;

class Groups extends \Aksara\Laboratory\Core
{
    private $_table = 'app__groups';

    public function __construct()
    {
        parent::__construct();

        $this->restrict_on_demo();

        $this->set_permission();
        $this->set_theme('backend');

        $this->unset_method('clone');

        $this->unset_delete('group_id', [1, 2, 3]);
    }

    public function index()
    {
        $this->set_title(phrase('Manage Groups'))
        ->set_icon('mdi mdi-key')
        ->set_description('
            <div class="row">
                <div class="col-12">
                    ' . phrase('The listed privileges below is created automatically from') . '
                    &nbsp;
                    <a href="' . go_to('privileges', ['per_page' => null]) . '" class="badge bg-secondary --xhr">
                        <i class="mdi mdi-arrow-right"></i>
                        ' . phrase('Privileges Adjustment') . '
                    </a>
                </div>
            </div>
        ')
        ->unset_column('group_id, group_privileges')
        ->unset_view('group_id')
        ->unset_field('group_id')
        ->set_field([
            'group_description' => 'textarea',
            'status' => 'boolean'
        ])
        ->set_field('group_privileges', 'custom_format', $this->_privileges())
        ->set_validation([
            'group_name' => 'required',
            'group_description' => 'required'
        ])
        ->set_alias([
            'group_name' => phrase('Group Name'),
            'group_description' => phrase('Description'),
            'group_privileges' => phrase('Privileges'),
            'status' => phrase('Status')
        ])

        ->modal_size('modal-lg')

        ->order_by('group_id')

        ->render($this->_table);
    }

    private function _privileges()
    {
        if (! in_array($this->get_method(), ['create', 'read', 'update'])) {
            return false;
        }

        $modules_collection = $this->model->select('
            path,
            privileges
        ')
        ->order_by('path')
        ->get('app__groups_privileges')
        ->result();

        $current = $this->model->select('
            group_privileges
        ')
        ->get_where(
            $this->_table,
            [
                'group_id' => service('request')->getGet('group_id')
            ],
            1
        )
        ->row('group_privileges');

        $current = ($current ? json_decode($current, true) : []);
        $output = null;

        if ($modules_collection) {
            $prepare = [];
            $modules = [];

            foreach ($modules_collection as $key => $val) {
                $path = str_replace('/', '__', $val->path);
                $privilege_output = null;
                $privileges = ($val->privileges ? json_decode($val->privileges) : new \stdClass());

                if (! $privileges) {
                    continue;
                }

                foreach ($privileges as $key => $privilege) {
                    if ('index' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-primary';
                    } elseif ('create' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-success';
                    } elseif ('read' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-info';
                    } elseif ('update' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-warning';
                    } elseif ('delete' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-danger';
                    } elseif ('export' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-success';
                    } elseif ('print' == $privilege) {
                        $label = phrase(ucfirst($privilege));
                        $badge_color = 'bg-warning';
                    } elseif ('pdf' == $privilege) {
                        $label = phrase(strtoupper($privilege));
                        $badge_color = 'bg-danger';
                    } else {
                        $label = phrase(ucwords(str_replace('_', ' ', $privilege)));
                        $badge_color = 'bg-secondary';
                    }

                    if ('read' === $this->get_method()) {
                        if (isset($current[$val->path]) && in_array($privilege, $current[$val->path])) {
                            $privilege_output .= '
                                <div class="col-6 col-md-3">
                                    <label class="d-block mb-0"' . (strlen($label) > 12 ? ' data-bs-toggle="tooltip" title="' . $label . '"' : null) . '>
                                        <span class="badge text-dark pe-0 ps-0"><i class="mdi mdi-check text-primary"></i> ' . $label . '</span>
                                    </label>
                                </div>
                            ';
                        }
                    } else {
                        $privilege_output .= '
                            <div class="col-6 col-md-3 bg-white">
                                <div class="form-check form-switch mb-0"' . (strlen($label) > 12 ? ' data-bs-toggle="tooltip" title="' . $label . '"' : null) . '>
                                    <input type="checkbox" name="group_privileges[' . $val->path . '][]" value="' . $privilege . '" class="form-check-input checker-children" id="' . $path . '_' . $privilege . '"' . (isset($current[$val->path]) && in_array($privilege, $current[$val->path]) ? ' checked' : '') . ' />
                                    <label class="form-check-label text-sm" for="' . $path . '_' . $privilege . '">
                                        ' . $label . '
                                    </label>
                                </div>
                            </div>
                        ';
                    }
                }

                $paths = explode('/', $val->path);
                list($module) = array_pad($paths, 1, null);
                $module_path = null;

                foreach ($paths as $_key => $_val) {
                    $module_path .= ($_key ? ' &gt; ' : null) . phrase(ucwords(str_replace('_', ' ', $_val)));
                }

                $output .= '
                    ' . (! in_array($module, $modules) ? '
                    ' . ($modules ? '<hr />' : null) . '
                    <a href="' . base_url($val->path) . '" target="_blank">
                        <b>
                            <i class="mdi mdi-puzzle-outline"></i>
                            &nbsp;
                            ' . phrase('Module') . ': ' . phrase(ucwords(str_replace('_', ' ', $module))) . '
                        </b>
                    </a>
                    ' : '') . '
                    <div class="check-group w-100">
                        <div class="form-check form-switch">
                            ' . (in_array($this->get_method(), ['create', 'update']) ? '<input type="checkbox" class="form-check-input" id="' . $path . '" data-bs-toggle="tooltip" title="' . phrase('Check all') . '" role="checker" data-parent=".check-group" />' : '<input type="checkbox" class="form-check-input" checked disabled />') . '
                            <label class="form-check-label fw-bold" for="' . $path . '">
                                ' . $module_path . '
                            </label>
                            <a href="' . base_url($val->path) . '" target="_blank">
                                <i class="mdi mdi-launch"></i>
                            </a>
                        </div>
                        <div class="row mb-3">
                            ' . $privilege_output . '
                        </div>
                    </div>
                ';

                if (! in_array($module, $modules)) {
                    $modules[] = $module;
                }
            }
        }

        return $output;
    }
}
