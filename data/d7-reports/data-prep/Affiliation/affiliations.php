$view = new view();
$view->name = 'export_affiliations';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'users';
$view->human_name = 'Export_Affiliations';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Affiliations';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['access']['perm'] = 'access user profiles';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: User: Name */
$handler->display->display_options['fields']['name']['id'] = 'name';
$handler->display->display_options['fields']['name']['table'] = 'users';
$handler->display->display_options['fields']['name']['field'] = 'name';
$handler->display->display_options['fields']['name']['label'] = '';
$handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
$handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
$handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
$handler->display->display_options['fields']['name']['link_to_user'] = FALSE;
/* Field: User: Uid */
$handler->display->display_options['fields']['uid']['id'] = 'uid';
$handler->display->display_options['fields']['uid']['table'] = 'users';
$handler->display->display_options['fields']['uid']['field'] = 'uid';
$handler->display->display_options['fields']['uid']['label'] = 'facstaff_nid';
$handler->display->display_options['fields']['uid']['link_to_user'] = FALSE;
/* Field: User: User Departments */
$handler->display->display_options['fields']['field_user_departments']['id'] = 'field_user_departments';
$handler->display->display_options['fields']['field_user_departments']['table'] = 'field_data_field_user_departments';
$handler->display->display_options['fields']['field_user_departments']['field'] = 'field_user_departments';
$handler->display->display_options['fields']['field_user_departments']['label'] = 'department_nid';
$handler->display->display_options['fields']['field_user_departments']['type'] = 'node_reference_nid';
$handler->display->display_options['fields']['field_user_departments']['group_rows'] = FALSE;
$handler->display->display_options['fields']['field_user_departments']['delta_offset'] = '0';
/* Sort criterion: User: Name */
$handler->display->display_options['sorts']['name']['id'] = 'name';
$handler->display->display_options['sorts']['name']['table'] = 'users';
$handler->display->display_options['sorts']['name']['field'] = 'name';
/* Filter criterion: User: Active status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'users';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['value'] = '1';
$handler->display->display_options['filters']['status']['group'] = 1;
$handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
/* Filter criterion: User: User Departments (field_user_departments) */
$handler->display->display_options['filters']['field_user_departments_nid']['id'] = 'field_user_departments_nid';
$handler->display->display_options['filters']['field_user_departments_nid']['table'] = 'field_data_field_user_departments';
$handler->display->display_options['filters']['field_user_departments_nid']['field'] = 'field_user_departments_nid';
$handler->display->display_options['filters']['field_user_departments_nid']['operator'] = 'not empty';
/* Filter criterion: User: User Departments (field_user_departments) */
$handler->display->display_options['filters']['field_user_departments_nid_1']['id'] = 'field_user_departments_nid_1';
$handler->display->display_options['filters']['field_user_departments_nid_1']['table'] = 'field_data_field_user_departments';
$handler->display->display_options['filters']['field_user_departments_nid_1']['field'] = 'field_user_departments_nid';
$handler->display->display_options['filters']['field_user_departments_nid_1']['operator'] = 'not in';
$handler->display->display_options['filters']['field_user_departments_nid_1']['value'] = array(
  15148 => '15148',
  15109 => '15109',
  15151 => '15151',
);

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'affiliations-01.csv';
