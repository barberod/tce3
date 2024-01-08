$view = new view();
$view->name = 'export_institutions';
$view->description = '';
$view->tag = 'tce3dataprep';
$view->base_table = 'node';
$view->human_name = 'Export_Institutions';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Institutions';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: Content: university dapip id */
$handler->display->display_options['fields']['field_university_dapip_id']['id'] = 'field_university_dapip_id';
$handler->display->display_options['fields']['field_university_dapip_id']['table'] = 'field_data_field_university_dapip_id';
$handler->display->display_options['fields']['field_university_dapip_id']['field'] = 'field_university_dapip_id';
$handler->display->display_options['fields']['field_university_dapip_id']['label'] = 'DAPIP_ID';
$handler->display->display_options['fields']['field_university_dapip_id']['type'] = 'text_plain';
/* Field: Content: Title */
$handler->display->display_options['fields']['title']['id'] = 'title';
$handler->display->display_options['fields']['title']['table'] = 'node';
$handler->display->display_options['fields']['title']['field'] = 'title';
$handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
$handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
$handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
/* Field: Content: university address */
$handler->display->display_options['fields']['field_university_address']['id'] = 'field_university_address';
$handler->display->display_options['fields']['field_university_address']['table'] = 'field_data_field_university_address';
$handler->display->display_options['fields']['field_university_address']['field'] = 'field_university_address';
$handler->display->display_options['fields']['field_university_address']['label'] = 'Address';
$handler->display->display_options['fields']['field_university_address']['type'] = 'text_plain';
/* Field: Content: university import number */
$handler->display->display_options['fields']['field_university_import_number']['id'] = 'field_university_import_number';
$handler->display->display_options['fields']['field_university_import_number']['table'] = 'field_data_field_university_import_number';
$handler->display->display_options['fields']['field_university_import_number']['field'] = 'field_university_import_number';
$handler->display->display_options['fields']['field_university_import_number']['label'] = 'Import_Number';
$handler->display->display_options['fields']['field_university_import_number']['type'] = 'text_plain';
/* Field: Content: university showOrHide */
$handler->display->display_options['fields']['field_university_showorhide']['id'] = 'field_university_showorhide';
$handler->display->display_options['fields']['field_university_showorhide']['table'] = 'field_data_field_university_showorhide';
$handler->display->display_options['fields']['field_university_showorhide']['field'] = 'field_university_showorhide';
$handler->display->display_options['fields']['field_university_showorhide']['label'] = 'Show_or_Hide';
/* Field: Content: university state */
$handler->display->display_options['fields']['field_university_state']['id'] = 'field_university_state';
$handler->display->display_options['fields']['field_university_state']['table'] = 'field_data_field_university_state';
$handler->display->display_options['fields']['field_university_state']['field'] = 'field_university_state';
$handler->display->display_options['fields']['field_university_state']['label'] = 'State';
/* Field: Content: university text */
$handler->display->display_options['fields']['field_university_text']['id'] = 'field_university_text';
$handler->display->display_options['fields']['field_university_text']['table'] = 'field_data_field_university_text';
$handler->display->display_options['fields']['field_university_text']['field'] = 'field_university_text';
$handler->display->display_options['fields']['field_university_text']['label'] = 'Text';
$handler->display->display_options['fields']['field_university_text']['type'] = 'text_plain';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid']['id'] = 'nid';
$handler->display->display_options['fields']['nid']['table'] = 'node';
$handler->display->display_options['fields']['nid']['field'] = 'nid';
/* Sort criterion: Content: Nid */
$handler->display->display_options['sorts']['nid']['id'] = 'nid';
$handler->display->display_options['sorts']['nid']['table'] = 'node';
$handler->display->display_options['sorts']['nid']['field'] = 'nid';
/* Filter criterion: Content: Published status */
$handler->display->display_options['filters']['status']['id'] = 'status';
$handler->display->display_options['filters']['status']['table'] = 'node';
$handler->display->display_options['filters']['status']['field'] = 'status';
$handler->display->display_options['filters']['status']['value'] = 1;
$handler->display->display_options['filters']['status']['group'] = 1;
$handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
/* Filter criterion: Content: Type */
$handler->display->display_options['filters']['type']['id'] = 'type';
$handler->display->display_options['filters']['type']['table'] = 'node';
$handler->display->display_options['filters']['type']['field'] = 'type';
$handler->display->display_options['filters']['type']['value'] = array(
  'university' => 'university',
);

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'none';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'institutions.csv';
