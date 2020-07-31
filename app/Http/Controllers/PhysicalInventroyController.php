<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PhysicalInventory;
use App\Model\Category;
use App\Model\Department;
use App\Model\Vendor;
use App\Model\Subcategory;
use Illuminate\Support\Facades\DB;
use App\Model\WebAdminSetting;
use Pagination;

class PhysicalInventroyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $physicalInventrorylists = PhysicalInventory::orderBy('ipiid', 'ASC')->paginate(25);
        return view('physicalInventroy.index', compact('physicalInventrorylists'));
    }

    public function create()
    {
        $items = DB::connection('mysql_dynamic')->select('SELECT mst_item.vitemname, mst_item.vbarcode, mst_item.dunitprice, mst_item.dcostprice, mst_department.vdepartmentname, mst_category.vcategoryname, mst_supplier.vcompanyname,mst_subcategory.subcat_name, mst_item.iqtyonhand FROM mst_item join mst_department on mst_item.vdepcode = mst_department.idepartmentid join mst_category on mst_item.vcategorycode = mst_category.icategoryid join mst_supplier on mst_item.vsuppliercode = mst_supplier.isupplierid join mst_subcategory on mst_item.subcat_id = mst_subcategory.subcat_id');

        $departments = Department::orderBy('vdepartmentname', 'ASC')->get();
        $categories = Category::orderBy('vcategoryname', 'ASC')->get();
        $subcategories = Subcategory::orderBy('subcat_name', 'ASC')->get();
        $vendors = Vendor::orderBy('vcompanyname', 'ASC')->get();

        // dd($vendors);

        return view('physicalInventroy.create', compact('items', 'departments', 'categories', 'subcategories', 'vendors' ));
    }

    public function get_item_list(Request $request){

        $input = $request->all();
	    // $this->load->language('administration/new_physical_inventory_detail_list');

	    // $this->document->setTitle($this->language->get('heading_title'));

        if (isset($input['sort'])) {
            $sort = $input['sort'];
        } else {
            $sort = 'iitemid';
        }
        if (isset($input['page'])) {
            $page = $input['page'];
        } else {
            $page = 1;
        }
        $url = '';
        if (isset($input['page'])) {
            $url .= '&page=' . $input['page'];
        }

                if (isset($input['searchbox'])) {
                    $searchbox =  $input['searchbox'];
                    $this->session->data['item_page_search'] = $input['searchbox'];
                }elseif($request->session()->has('item_page_search') && isset($input['cancel_btn'])){
                    $searchbox =  session()->get('item_page_search');
                }elseif($request->session()->has('item_page_search_id')){
                    $searchbox =  session()->get('item_page_search_id');
                    session()->forget('item_page_search_id');
                }else{
                    $searchbox = '';
                    session()->forget('item_page_search');
                }


                $disable_items = 'Active';


                if(isset($input['sort_items'])){
                    $sort_items =  $input['sort_items'];
                }else{
                    $sort_items = '';
                }

                $url .= '&show_items=' . $disable_items;
                $data['show_items'] = $disable_items;

                $url .= '&sort_items=' . $sort_items;
                $data['sort_items'] = $sort_items;

                // $data['breadcrumbs'] = array();

                // $data['breadcrumbs'][] = array(
                //     'text' => $this->language->get('text_home'),
                //     'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
                // );

                // $data['breadcrumbs'][] = array(
                //     'text' => $this->language->get('heading_title'),
                //     'href' => $this->url->link('administration/new_physical_inventory_detail')
                // );

                $data['url_next'] = url('physicalInventroy/snapshot');
                $data['searchitem'] = url('physicalInventroy/search');
                $data['parent_child_search'] = url('administration/items/parent_child_search');
                $data['current_url'] = url('physicalInventroy');
                $data['session_url'] = url('physicalInventroy/create_session');
                $data['get_data_by_barcode'] = url('physicalInventroy/get_barcode');
                $data['scanned_session_url'] = url('physicalInventroy/create_scanned_session');
                $data['get_scanned_data'] = url('physicalInventroy/get_scanned_data');
                $data['remove_session_scanned_data'] = url('physicalInventroy/remove_session_scanned_data');
                $data['unset_session_scanned_data'] = url('physicalInventroy/unset_session_scanned_data');

                $data['get_categories_url'] = url('physicalInventroy/get_categories_by_department');
                $data['get_subcategories_url'] = url('physicalInventroy/get_subcat_by_categories');

                $data['cancel'] = url('physicalInventroy');

                $data['items'] = array();

                $limit  = 25;
                $filter_data = array(
                    'searchbox'  => $searchbox,
                    'sort_items'  => $sort_items,
                    'show_items' => $disable_items,
                    'start' => ($page - 1) * $limit,
                    'limit' => $limit
                );

                // $this->load->model('administration/menus');

                // $this->load->model('tool/image');

                // $this->load->model('api/items');

                // $this->load->model('administration/items');

                // $this->load->model('administration/physical_inventory_detail');

        //============= Start Department Data=====================================================

                $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
                $departments_html ="";
                $departments_html = "<select class='form-control' multiple='true' name='dept_code[]' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
                foreach($departments as $department){
                    if(isset($vdepcode) && $vdepcode == $department['vdepcode']){
                        $departments_html .= "<option value='".$department['vdepcode']."' selected='selected'>".$department['vdepartmentname']."</option>";
                    } else {
                        $departments_html .= "<option value='".$department['vdepcode']."'>".$department['vdepartmentname']."</option>";
                    }
                }
                $departments_html .="</select>";

                $data['departments'] = $departments_html;

        //=============End Department Data=====================================================

        //=============Start Category Data=====================================================

                $category = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
                $category_html ="";
                $category_html = "<select class='form-control' multiple='true' name='category_code[]' id='category_code' style='width: 100px;'>'<option value='all'>All</option>";
                // foreach($category as $category){
                //     if(isset($vcategorycode) && $vcategorycode == $category['vcategorycode']){
                //         $category_html .= "<option value='".$category['vcategorycode']."' selected='selected'>".$category['vcategoryname']."</option>";
                //     } else {
                //         $category_html .= "<option value='".$category['vcategorycode']."'>".$category['vcategoryname']."</option>";
                //     }
                // }
                $category_html .="</select>";

                $data['category'] = $category_html;
        //=============End Category Data=====================================================

        //=============Start Sub Category Data=====================================================

                $subcategory = Subcategory::orderBy('subcat_name', 'ASC')->get()->toArray();
                $subcategory_html ="";
                $subcategory_html = "<select class='form-control' multiple='true' name='subcat_id[]' id='subcat_id' style='width: 100px;'>'<option value='all'>All</option>";
                // foreach($subcategory as $subcategory){
                //     if(isset($subcat_id) && $subcat_id == $subcategory['subcat_id']){
                //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."' selected='selected'>".$subcategory['subcat_name']."</option>";
                //     } else {
                //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."'>".$subcategory['subcat_name']."</option>";
                //     }
                // }
                $subcategory_html .="</select>";

                $data['subcategory'] = $subcategory_html;
        //=============End Sub Category Data=====================================================

        //=============Start Supplier Data=====================================================

                $supplier = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();

                // echo "<pre>"; print_r($supplier); echo "</pre>"; die;

                $supplier_html ="";
                $supplier_html = "<select class='form-control' multiple='true' name='supplier_code[]' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
                foreach($supplier as $supplier){
                    if(isset($vsuppliercode) && $vsuppliercode == $supplier['vsuppliercode']){
                        $supplier_html .= "<option value='".$supplier['vsuppliercode']."' selected='selected'>".$supplier['vcompanyname']."</option>";
                    } else {
                        $supplier_html .= "<option value='".$supplier['vsuppliercode']."'>".$supplier['vcompanyname']."</option>";
                    }
                }
                $supplier_html .="</select>";

                $data['supplier'] = $supplier_html;
        //=============End Supplier Data=====================================================

        //==============Start Price select By========================================================

                $price_select_by_list = array(
                                            ''          => 'Select',
                                            'greater'   => 'Greater than',
                                            'less'      => 'Less than',
                                            'equal'     => 'Equal to',
                                            'between'   => 'Between'
                                        );

                $price_select_by_html = "<select class='' id='price_select_by' name='price_select_by' style='width:70px;color:black;border-radius: 4px;height:28px;'>";
                foreach($price_select_by_list as $k => $v){
                    $price_select_by_html .= "<option value='".$k."'";

                    if(isset($data['price_select_by']) && $k === $data['price_select_by']){
                        $price_select_by_html .= " selected";
                    }

                    $price_select_by_html .= ">".$v."</option>";
                }
                $price_select_by_html .= "</select>";
                $price_select_by_html .= "<span id='selectByValuesSpan'>";

                // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_1']."'/>";



                if(isset($input['price_select_by']) && $input['price_select_by'] === 'between'){
                    // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
                    $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
                    $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value='".$data['select_by_value_2']."'/>";
                } else {
                    $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
                }

                $price_select_by_html .= "</span>";

                $data['price_select_by'] = $price_select_by_html;

        //==============End Price select By========================================================

                 $data['itemListings'] = array("vdepcode"=>"Department", "vcategorycode"=>"Category", "subcat_id"=>"Sub Category", "vsuppliercode"=>"Supplier");


                $item_data = PhysicalInventory::first()->getItemsResult($filter_data);

                $item_total = PhysicalInventory::first()->getTotalItems($filter_data);


                $results = $item_data;
                if(count($data['itemListings']) > 0){
                    foreach ($results as $k => $result) {
// dd($result);
                        $data['items'][$k]['iitemid'] = $result->iitemid;
                        $data['items'][$k]['dunitprice'] = $result->dunitprice;
                        $data['items'][$k]['vbarcode'] = $result->vbarcode;
                        $data['items'][$k]['VITEMNAME'] = $result->VITEMNAME;


                        foreach($data['itemListings'] as $m => $v){

                            if($m == 'vdepcode'){
                                $data['items'][$k][$m] = $result->$m;
                                $data['items'][$k]['vdepartmentname'] = $result->vdepartmentname;
                            }else if($m == 'vcategorycode'){
                                $data['items'][$k][$m] = $result->$m;
                                $data['items'][$k]['vcategoryname'] = $result->vcategoryname;
                            }else if($m == 'subcat_id'){
                              $data['items'][$k][$m] = $result->$m;
                              $data['items'][$k]['subcat_name'] = $result->subcat_name;
                            }else if($m == 'vsuppliercode'){
                                $data['items'][$k][$m] = $result->$m;
                                $data['items'][$k]['vcompanyname'] = $result->vcompanyname;
                            }else{
                                $data['items'][$k][$m] = $result->$m;
                            }
                        }

                        $data['items'][$k]['QOH'] = $result->IQTYONHAND;
                        $data['items'][$k]['isparentchild'] = $result->isparentchild;

                    }

                }else{
                    foreach ($results as $result) {

                        $data['items'][] = array(
                            'iitemid'           => $result->iitemid,
                            'dunitprice'        => $result->dunitprice,
                            'vitemname'         => $result->vitemname,
                            'VITEMNAME'         => $result->VITEMNAME,
                            'vbarcode'          => $result->vbarcode,
                            'vcategorycode'     => $result->vcategorycode,
                            'vcategoryname'     => $result->vcategoryname,
                            'subcat_id'         => $result->subcat_id,
                            'subcat_name'       => $result->subcat_name,
                            'vdepcode'          => $result->vdepcode,
                            'vdepartmentname'   => $result->vdepartmentname,
                            'vsuppliercode'     => $result->vsuppliercode,
                            'vcompanyname'      => $result->vcompanyname,
                            'iqtyonhand'        => $result->iqtyonhand,
                            'QOH'               => $result->IQTYONHAND,
                            'isparentchild'     => $result->isparentchild,
                            );
                    }
                }

                if(count($item_data)==0){
                    $data['items'] =array();
                    $item_total = 0;
                    $data['item_row'] =1;
                }

                // $data['heading_title'] = $this->language->get('heading_title');

                // $data['column_itemname'] = $this->language->get('column_itemname');
                // $data['column_itemtype'] = $this->language->get('column_itemtype');
                // $data['column_action'] = $this->language->get('column_action');
                // $data['column_deptcode'] = $this->language->get('column_deptcode');
                // $data['column_sku'] = $this->language->get('column_sku');
                // $data['column_categorycode'] = $this->language->get('column_categorycode');
                // $data['column_price'] = $this->language->get('column_price');
                // $data['column_cost'] = $this->language->get('column_cost');
                // $data['column_costtotal'] = $this->language->get('column_costtotal');
                // $data['column_pricetotal'] = $this->language->get('column_pricetotal');
                // $data['column_qtyonhand'] = $this->language->get('column_qtyonhand');
                // $data['column_subcat'] = $this->language->get('column_subcat');
                // $data['column_supplier'] = $this->language->get('column_supplier');

                // $data['button_remove'] = $this->language->get('button_remove');
                // $data['button_save'] = $this->language->get('button_save');
                // $data['button_view'] = $this->language->get('button_view');
                // $data['button_add'] = $this->language->get('button_add');
                // $data['button_edit'] = $this->language->get('button_edit');
                // $data['button_delete'] = $this->language->get('button_delete');
                // $data['button_rebuild'] = $this->language->get('button_rebuild');

                // $data['button_edit_list'] = 'Update Selected';
                // $data['text_special'] = '<strong>Special:</strong>';

                // $data['token'] = $this->session->data['token'];


                if (isset($this->error['warning'])) {
                    $data['error_warning'] = $this->error['warning'];
                } else {
                    $data['error_warning'] = '';
                }

                if (isset($this->session->data['success'])) {
                    $data['success'] = $this->session->data['success'];

                    unset($this->session->data['success']);
                } else {
                    $data['success'] = '';
                }

                if (isset($input['selected'])) {
                    $data['selected'] = (array)$input['selected'];
                } else {
                    $data['selected'] = array();
                }

                if (isset($input['page'])) {
                    $url .= '&page=' . $input['page'];
                }

                $url = '';

                if (isset($disable_items)) {
                    $url .= '&show_items=' . $disable_items;
                }

                if (isset($sort_items)) {
                    $url .= '&sort_items=' . $sort_items;
                }

                if(isset($sort_items) && $sort_items != ''){
                    if($sort_items == 'DESC'){
                        $url1 = '&show_items=' . $disable_items.'&sort_items=ASC';
                    }else{
                        $url1 = '&show_items=' . $disable_items.'&sort_items=DESC';
                    }
                }else{
                    $url1 = '&show_items=' . $disable_items.'&sort_items=ASC';
                }
                // $data['item_sorting'] = $this->url->link('administration/items', 'token=' . $this->session->data['token'] . $url1, true);

                $data['title_arr'] = array(
                                        'webstore' => 'Web Store',
                                        'dunitprice' => 'Price',
                                        'vitemcode' => 'Item Code',
                                        'vitemname' => 'Item Name',
                                        'vunitcode' => 'Unit',
                                        'vbarcode' => 'SKU',
                                        'vpricetype' => 'Price Type',
                                        'vcategorycode' => 'Category',
                                        'subcat_id'     => 'Sub Category',
                                        'vdepcode' => 'Dept.',
                                        'vsuppliercode' => 'Supplier',
                                        'iqtyonhand' => 'Qty. on Hand',
                                        'estatus' => 'Status',
                                        'isparentchild' => 'is parent child',
                                        'parentid' => 'parent id',
                                        'parentmasterid' => 'parent master id',
                                        'wicitem' => 'wic item',
                                    );

                // $pagination = new Pagination();
                // $pagination->total = $item_total;
                // $pagination->page = $page;
                // $pagination->limit = 25;
                // $pagination->url = url('administration/items');

                // $data['pagination'] = $pagination->render();

                // $data['results'] = sprintf($this->language->get('text_pagination'), ($item_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($item_total - $this->config->get('config_limit_admin'))) ? $item_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $item_total, ceil($item_total / $this->config->get('config_limit_admin')));

                // $data['header'] = $this->load->controller('common/header');
                // $data['column_left'] = $this->load->controller('common/column_left');
                // $data['footer'] = $this->load->controller('common/footer');


                // $this->response->setOutput($this->load->view('administration/new_physical_inventory_item_list', $data));

                return view('physicalInventroy.get_item_list', compact('data'));
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
