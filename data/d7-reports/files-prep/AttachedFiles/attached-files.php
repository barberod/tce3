$view = new view();
$view->name = 'export_supplemental_files_info';
$view->description = '';
$view->tag = 'tce3filesprep';
$view->base_table = 'node';
$view->human_name = 'Export_Supplemental_Files_Info';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Supplemental_Files_Info';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: Content: Supp Eval Ref */
$handler->display->display_options['fields']['field_supp_eval_ref']['id'] = 'field_supp_eval_ref';
$handler->display->display_options['fields']['field_supp_eval_ref']['table'] = 'field_data_field_supp_eval_ref';
$handler->display->display_options['fields']['field_supp_eval_ref']['field'] = 'field_supp_eval_ref';
$handler->display->display_options['fields']['field_supp_eval_ref']['label'] = 'evaluation_nid';
$handler->display->display_options['fields']['field_supp_eval_ref']['type'] = 'node_reference_nid';
/* Field: Content: Supp File */
$handler->display->display_options['fields']['field_supp_file']['id'] = 'field_supp_file';
$handler->display->display_options['fields']['field_supp_file']['table'] = 'field_data_field_supp_file';
$handler->display->display_options['fields']['field_supp_file']['field'] = 'field_supp_file';
$handler->display->display_options['fields']['field_supp_file']['label'] = 'file_path';
$handler->display->display_options['fields']['field_supp_file']['click_sort_column'] = 'fid';
$handler->display->display_options['fields']['field_supp_file']['type'] = 'file_url_plain';
/* Sort criterion: Content: Post date */
$handler->display->display_options['sorts']['created']['id'] = 'created';
$handler->display->display_options['sorts']['created']['table'] = 'node';
$handler->display->display_options['sorts']['created']['field'] = 'created';
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
  'supplemental_file' => 'supplemental_file',
);
/* Filter criterion: Content: Post date */
$handler->display->display_options['filters']['created']['id'] = 'created';
$handler->display->display_options['filters']['created']['table'] = 'node';
$handler->display->display_options['filters']['created']['field'] = 'created';
$handler->display->display_options['filters']['created']['operator'] = '>=';
$handler->display->display_options['filters']['created']['value']['value'] = '2022-05-12 15:15:00';

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '3000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'supp-files-info-01.csv';
