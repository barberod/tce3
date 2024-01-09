$view = new view();
$view->name = 'tce3_affilaitions';
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
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Relationship: User: User Departments (field_user_departments) */
$handler->display->display_options['relationships']['field_user_departments_nid']['id'] = 'field_user_departments_nid';
$handler->display->display_options['relationships']['field_user_departments_nid']['table'] = 'field_data_field_user_departments';
$handler->display->display_options['relationships']['field_user_departments_nid']['field'] = 'field_user_departments_nid';
$handler->display->display_options['relationships']['field_user_departments_nid']['required'] = TRUE;
$handler->display->display_options['relationships']['field_user_departments_nid']['delta'] = '-1';
/* Field: User: Name */
$handler->display->display_options['fields']['name']['id'] = 'name';
$handler->display->display_options['fields']['name']['table'] = 'users';
$handler->display->display_options['fields']['name']['field'] = 'name';
$handler->display->display_options['fields']['name']['label'] = 'username';
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
/* Filter criterion: Content: Nid */
$handler->display->display_options['filters']['nid']['id'] = 'nid';
$handler->display->display_options['filters']['nid']['table'] = 'node';
$handler->display->display_options['filters']['nid']['field'] = 'nid';
$handler->display->display_options['filters']['nid']['relationship'] = 'field_user_departments_nid';
$handler->display->display_options['filters']['nid']['operator'] = '!=';
$handler->display->display_options['filters']['nid']['value']['value'] = '15148';

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'affiliations-01.csv';
