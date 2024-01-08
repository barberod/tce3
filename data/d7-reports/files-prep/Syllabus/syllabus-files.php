$view = new view();
$view->name = 'export_syllabus_files_info';
$view->description = '';
$view->tag = 'tce3filesprep';
$view->base_table = 'node';
$view->human_name = 'Export_Syllabus_Files_Info';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['title'] = 'Export_Syllabus_Files_Info';
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'perm';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['style_plugin'] = 'default';
$handler->display->display_options['row_plugin'] = 'fields';
/* Field: Content: Nid */
$handler->display->display_options['fields']['nid']['id'] = 'nid';
$handler->display->display_options['fields']['nid']['table'] = 'node';
$handler->display->display_options['fields']['nid']['field'] = 'nid';
$handler->display->display_options['fields']['nid']['label'] = 'evaluation_nid';
/* Field: Content: Eval Syllabus */
$handler->display->display_options['fields']['field_eval_syllabus']['id'] = 'field_eval_syllabus';
$handler->display->display_options['fields']['field_eval_syllabus']['table'] = 'field_data_field_eval_syllabus';
$handler->display->display_options['fields']['field_eval_syllabus']['field'] = 'field_eval_syllabus';
$handler->display->display_options['fields']['field_eval_syllabus']['label'] = 'file_path';
$handler->display->display_options['fields']['field_eval_syllabus']['click_sort_column'] = 'fid';
$handler->display->display_options['fields']['field_eval_syllabus']['type'] = 'file_url_plain';
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
  'evaluation' => 'evaluation',
);
/* Filter criterion: Content: Eval Syllabus (field_eval_syllabus:fid) */
$handler->display->display_options['filters']['field_eval_syllabus_fid']['id'] = 'field_eval_syllabus_fid';
$handler->display->display_options['filters']['field_eval_syllabus_fid']['table'] = 'field_data_field_eval_syllabus';
$handler->display->display_options['filters']['field_eval_syllabus_fid']['field'] = 'field_eval_syllabus_fid';
$handler->display->display_options['filters']['field_eval_syllabus_fid']['operator'] = 'not empty';
/* Filter criterion: Content: Nid */
$handler->display->display_options['filters']['nid']['id'] = 'nid';
$handler->display->display_options['filters']['nid']['table'] = 'node';
$handler->display->display_options['filters']['nid']['field'] = 'nid';
$handler->display->display_options['filters']['nid']['operator'] = '>=';
$handler->display->display_options['filters']['nid']['value']['value'] = '362868';

/* Display: Data export */
$handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-01.csv';
$handler->display->display_options['sitename_title'] = 0;

/* Display: Data export-02 */
$handler = $view->new_display('views_data_export', 'Data export-02', 'views_data_export_2');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '1000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-02.csv';

/* Display: Data export-03 */
$handler = $view->new_display('views_data_export', 'Data export-03', 'views_data_export_3');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '2000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-03.csv';

/* Display: Data export-04 */
$handler = $view->new_display('views_data_export', 'Data export-04', 'views_data_export_4');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '3000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-04.csv';

/* Display: Data export-05 */
$handler = $view->new_display('views_data_export', 'Data export-05', 'views_data_export_5');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '4000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-05.csv';

/* Display: Data export-06 */
$handler = $view->new_display('views_data_export', 'Data export-06', 'views_data_export_6');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '5000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-06.csv';

/* Display: Data export-07 */
$handler = $view->new_display('views_data_export', 'Data export-07', 'views_data_export_7');
$handler->display->display_options['pager']['type'] = 'some';
$handler->display->display_options['pager']['options']['items_per_page'] = '1000';
$handler->display->display_options['pager']['options']['offset'] = '6000';
$handler->display->display_options['style_plugin'] = 'views_data_export_csv';
$handler->display->display_options['path'] = 'syllabi-info-07.csv';
$handler->display->display_options['sitename_title'] = 0;
