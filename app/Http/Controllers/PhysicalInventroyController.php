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
use Laravel\Ui\Presets\React;
use Pagination;

class PhysicalInventroyController extends Controller
{
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

        return view('physicalInventroy.create', compact('items', 'departments', 'categories', 'subcategories', 'vendors'));
    }

    public function get_item_list(Request $request)
    {

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
        } elseif ($request->session()->has('item_page_search') && isset($input['cancel_btn'])) {
            $searchbox =  session()->get('item_page_search');
        } elseif ($request->session()->has('item_page_search_id')) {
            $searchbox =  session()->get('item_page_search_id');
            session()->forget('item_page_search_id');
        } else {
            $searchbox = '';
            session()->forget('item_page_search');
        }


        $disable_items = 'Active';


        if (isset($input['sort_items'])) {
            $sort_items =  $input['sort_items'];
        } else {
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

        //============= Start Department Data=====================================================

        $departments = Department::orderBy('vdepartmentname', 'ASC')->get()->toArray();
        $departments_html = "";
        $departments_html = "<select class='form-control' multiple='true' name='dept_code[]' id='dept_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($departments as $department) {
            if (isset($vdepcode) && $vdepcode == $department['vdepcode']) {
                $departments_html .= "<option value='" . $department['vdepcode'] . "' selected='selected'>" . $department['vdepartmentname'] . "</option>";
            } else {
                $departments_html .= "<option value='" . $department['vdepcode'] . "'>" . $department['vdepartmentname'] . "</option>";
            }
        }
        $departments_html .= "</select>";

        $data['departments'] = $departments_html;

        //=============End Department Data=====================================================

        //=============Start Category Data=====================================================

        $category = Category::orderBy('vcategoryname', 'ASC')->get()->toArray();
        $category_html = "";
        $category_html = "<select class='form-control' multiple='true' name='category_code[]' id='category_code' style='width: 100px;'>'<option value='all'>All</option>";
        // foreach($category as $category){
        //     if(isset($vcategorycode) && $vcategorycode == $category['vcategorycode']){
        //         $category_html .= "<option value='".$category['vcategorycode']."' selected='selected'>".$category['vcategoryname']."</option>";
        //     } else {
        //         $category_html .= "<option value='".$category['vcategorycode']."'>".$category['vcategoryname']."</option>";
        //     }
        // }
        $category_html .= "</select>";

        $data['category'] = $category_html;
        //=============End Category Data=====================================================

        //=============Start Sub Category Data=====================================================

        $subcategory = Subcategory::orderBy('subcat_name', 'ASC')->get()->toArray();
        $subcategory_html = "";
        $subcategory_html = "<select class='form-control' multiple='true' name='subcat_id[]' id='subcat_id' style='width: 100px;'>'<option value='all'>All</option>";
        // foreach($subcategory as $subcategory){
        //     if(isset($subcat_id) && $subcat_id == $subcategory['subcat_id']){
        //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."' selected='selected'>".$subcategory['subcat_name']."</option>";
        //     } else {
        //         $subcategory_html .= "<option value='".$subcategory['subcat_id']."'>".$subcategory['subcat_name']."</option>";
        //     }
        // }
        $subcategory_html .= "</select>";

        $data['subcategory'] = $subcategory_html;
        //=============End Sub Category Data=====================================================

        //=============Start Supplier Data=====================================================

        $supplier = Vendor::orderBy('vcompanyname', 'ASC')->get()->toArray();

        // echo "<pre>"; print_r($supplier); echo "</pre>"; die;

        $supplier_html = "";
        $supplier_html = "<select class='form-control' multiple='true' name='supplier_code[]' id='supplier_code' style='width: 100px;'>'<option value='all'>All</option>";
        foreach ($supplier as $supplier) {
            if (isset($vsuppliercode) && $vsuppliercode == $supplier['vsuppliercode']) {
                $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "' selected='selected'>" . $supplier['vcompanyname'] . "</option>";
            } else {
                $supplier_html .= "<option value='" . $supplier['vsuppliercode'] . "'>" . $supplier['vcompanyname'] . "</option>";
            }
        }
        $supplier_html .= "</select>";

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
        foreach ($price_select_by_list as $k => $v) {
            $price_select_by_html .= "<option value='" . $k . "'";

            if (isset($data['price_select_by']) && $k === $data['price_select_by']) {
                $price_select_by_html .= " selected";
            }

            $price_select_by_html .= ">" . $v . "</option>";
        }
        $price_select_by_html .= "</select>";
        $price_select_by_html .= "<span id='selectByValuesSpan'>";

        // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_1']."'/>";



        if (isset($input['price_select_by']) && $input['price_select_by'] === 'between') {
            // $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box' placeholder='Enter Amt' style='width:56%;color:black;border-radius: 4px;height:28px;margin-left:5px;' value='".$data['select_by_value_2']."'/></span>";
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value=''/>";
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_2' id='select_by_value_2' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;padding-left: 1px;padding-right: 1px;margin-left:5px;' value='" . $data['select_by_value_2'] . "'/>";
        } else {
            $price_select_by_html .= "<input type='text' autocomplete='off' name='select_by_value_1' id='select_by_value_1' class='search_text_box1' placeholder='Enter Amt' style='width:60px;color:black;border-radius: 4px;height:28px;margin-left:5px;' value=''/>";
        }

        $price_select_by_html .= "</span>";

        $data['price_select_by'] = $price_select_by_html;

        //==============End Price select By========================================================

        $data['itemListings'] = array("vdepcode" => "Department", "vcategorycode" => "Category", "subcat_id" => "Sub Category", "vsuppliercode" => "Supplier");


        $item_data = PhysicalInventory::first()->getItemsResult($filter_data);

        $item_total = PhysicalInventory::first()->getTotalItems($filter_data);


        $results = $item_data;
        if (count($data['itemListings']) > 0) {
            foreach ($results as $k => $result) {

                $data['items'][$k]['iitemid'] = $result->iitemid;
                $data['items'][$k]['dunitprice'] = $result->dunitprice;
                $data['items'][$k]['vbarcode'] = $result->vbarcode;
                $data['items'][$k]['VITEMNAME'] = $result->VITEMNAME;


                foreach ($data['itemListings'] as $m => $v) {

                    if ($m == 'vdepcode') {
                        $data['items'][$k][$m] = $result->$m;
                        $data['items'][$k]['vdepartmentname'] = $result->vdepartmentname;
                    } else if ($m == 'vcategorycode') {
                        $data['items'][$k][$m] = $result->$m;
                        $data['items'][$k]['vcategoryname'] = $result->vcategoryname;
                    } else if ($m == 'subcat_id') {
                        $data['items'][$k][$m] = $result->$m;
                        $data['items'][$k]['subcat_name'] = $result->subcat_name;
                    } else if ($m == 'vsuppliercode') {
                        $data['items'][$k][$m] = $result->$m;
                        $data['items'][$k]['vcompanyname'] = $result->vcompanyname;
                    } else {
                        $data['items'][$k][$m] = $result->$m;
                    }
                }

                $data['items'][$k]['QOH'] = $result->IQTYONHAND;
                $data['items'][$k]['isparentchild'] = $result->isparentchild;
            }
        } else {
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

        if (count($item_data) == 0) {
            $data['items'] = array();
            $item_total = 0;
            $data['item_row'] = 1;
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

        if (isset($sort_items) && $sort_items != '') {
            if ($sort_items == 'DESC') {
                $url1 = '&show_items=' . $disable_items . '&sort_items=ASC';
            } else {
                $url1 = '&show_items=' . $disable_items . '&sort_items=DESC';
            }
        } else {
            $url1 = '&show_items=' . $disable_items . '&sort_items=ASC';
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


    public function search(Request $request)
    {
        ini_set('memory_limit', '256M');

        $input = $request->all();
        $return = $datas = array();

        $sort = "mi.LastUpdate DESC";
        if (isset($input['sort_items']) && !empty(trim($input['sort_items']))) {
            $sort_by = trim($input['sort_items']);
            $sort = "mi.vitemname $sort_by";
        }

        $show_condition = "WHERE mi.visinventory = 'Yes' AND mi.estatus='Active'";




        $search_value = $input['columns'];


        $search_itmes = [];
        foreach ($search_value as $value) {
            if ($value["data"] == "vitemname") {
                $search_items['vitemname'] = htmlspecialchars_decode($value['search']['value']);
            } else if ($value["data"] == "dunitprice") {
                $search_items['dunitprice'] = $value['search']['value'];
            } else if ($value["data"] == "vbarcode") {
                $search_items['vbarcode'] = $value['search']['value'];
            } else if ($value["data"] == "vcategoryname") {
                $search_items['vcategoryname'] = $value['search']['value'];
            } else if ($value["data"] == "subcat_name") {
                $search_items['subcat_name'] = $value['search']['value'];
            } else if ($value["data"] == "vcompanyname") {
                $search_items['vcompanyname'] = $value['search']['value'];
            } else if ($value["data"] == "vdepartmentname") {
                $search_items['vdepartmentname'] = $value['search']['value'];
            }
        }



        if (empty(trim($search_items['vitemname'])) && empty(trim($search_items['vbarcode'])) && empty(trim($search_items['dunitprice'])) && empty(trim($search_items['vcategoryname'])) && empty(trim($search_items['subcat_name'])) && empty(trim($search_items['vcompanyname'])) &&  empty(trim($search_items['vdepartmentname']))) {
            $limit = 20;

            $start_from = ($input['start']);

            $offset = $input['start'] + $input['length'];

            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname, mi.vdepcode, mi.subcat_id,mi.vsuppliercode, mi.vcategorycode, mi.vitemtype, md.vdepartmentname, mc.vcategoryname, (mi.dcostprice/mi.npack) as unitcost, mi.dunitprice, mi.nsaleprice, mi.subcat_id, msc.subcat_name, mi.iqtyonhand, mi.LastUpdate,msupp.vcompanyname, case isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition ORDER BY $sort LIMIT " . $start_from . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);

            $count_query = "SELECT DISTINCT(iitemid) FROM mst_item mi $show_condition";

            $run_count_query =  DB::connection('mysql_dynamic')->select($count_query);

            $count_records = $count_total = $run_count_query;
        } else {

            $limit = 20;

            $start_from = ($input['start']);

            $offset = $input['start'] + $input['length'];
            $condition = "";
            if (isset($search_items['vitemname']) && !empty(trim($search_items['vitemname']))) {
                $search = $search_items['vitemname'];
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
            }

            if (isset($search_items['vbarcode']) && !empty(trim($search_items['vbarcode']))) {
                $search = $search_items['vbarcode'];
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
            }

            if (isset($search_items['dunitprice']) && !empty(trim($search_items['dunitprice']))) {
                $search = $search_items['dunitprice'];
                $search_conditions = explode("|", $search);

                if ($search_conditions[0] == 'greater' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice > $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'less' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice < $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'equal' && isset($search_conditions[1])) {
                    $condition .= " AND mi.dunitprice = $search_conditions[1] ";
                } elseif ($search_conditions[0] == 'between' && isset($search_conditions[1]) && isset($search_conditions[2])) {
                    $condition .= " AND mi.dunitprice BETWEEN $search_conditions[1] AND $search_conditions[2] ";
                }
            }

            if (isset($search_items['vdepartmentname']) && !empty(trim($search_items['vdepartmentname']))) {
                $search = $search_items['vdepartmentname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vdepcode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['vcategoryname']) && !empty($search_items['vcategoryname'])) {
                $search = $search_items['vcategoryname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vcategorycode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['vcompanyname']) && !empty($search_items['vcompanyname'])) {
                $search = $search_items['vcompanyname'];
                if ($search != 'all') {
                    $condition .= " AND mi.vsuppliercode IN ('" . stripslashes($search) . "')";
                }
            }

            if (isset($search_items['subcat_name']) && !empty($search_items['subcat_name'])) {
                $search = $search_items['subcat_name'];
                if ($search != 'all') {
                    $condition .= " AND mi.subcat_id IN ('" . stripslashes($search) . "')";
                }
            }

            $select_query = "SELECT DISTINCT(mi.iitemid),mi.vbarcode,mi.vitemname,  mi.vdepcode, mi.subcat_id,mi.vsuppliercode, mi.vcategorycode, mi.vitemtype, md.vdepartmentname, mc.vcategoryname, (mi.dcostprice/mi.npack) as unitcost, mi.nsaleprice, mi.iqtyonhand, mi.dunitprice, mi.subcat_id, msc.subcat_name, mi.LastUpdate,msupp.vcompanyname,case isparentchild when 0 then mi.vitemname when 1 then Concat(mi.vitemname,' [Child]') when 2 then  Concat(mi.vitemname,' [Parent]') end   as VITEMNAME FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition " . " $condition ORDER BY $sort LIMIT " . $input['start'] . ", " . $limit;

            $query = DB::connection('mysql_dynamic')->select($select_query);

            $count_select_query = "SELECT COUNT(DISTINCT(mi.iitemid)) as count FROM mst_item as mi LEFT JOIN mst_department as md ON(mi.vdepcode = md.vdepcode) LEFT JOIN mst_category as mc ON(mi.vcategorycode = mc.vcategorycode) LEFT JOIN mst_subcategory as msc ON(mi.subcat_id=msc.subcat_id) LEFT JOIN mst_itemvendor as miv ON(mi.iitemid=miv.iitemid) LEFT JOIN mst_itemalias as mia ON(mi.vitemcode=mia.vitemcode) LEFT JOIN mst_supplier as msupp ON(mi.vsuppliercode = msupp.vsuppliercode) $show_condition " . " $condition";
            $count_query = DB::connection('mysql_dynamic')->select($count_select_query);

            $count_records = $count_total = (int)count($count_query);
        }

        $search = $input['search']['value'];


        // $this->load->model('api/items');

        $itemListings = array("vdepcode" => "Department", "vcategorycode" => "Category", "vsuppliercode" => "Supplier", "subcat_id" => "Sub Category", "iqtyonhand" => "Qty. on Hand");

        if (count($query) > 0) {
            foreach ($query as $key => $value) {

                $temp = array();
                $temp['iitemid'] = $value->iitemid;
                $temp['vbarcode'] = $value->vbarcode;
                $temp['vitemname'] = $value->VITEMNAME;
                $temp['dunitprice'] = $value->dunitprice;
                $temp['unitcost'] = number_format($value->unitcost, 2);

                if (count($itemListings) > 0) {
                    foreach ($itemListings as $m => $v) {
                        if ($m == 'vdepcode') {
                            $temp['vdepcode'] = $value->vdepcode;
                        } else if ($m == 'vcategorycode') {
                            $temp['vcategorycode'] = $value->vdepcode;
                        } else if ($m == 'vunitcode') {
                            $temp['subcat_id'] = $value->subcat_id;
                        } else if ($m == 'vsuppliercode') {
                            $temp['vsuppliercode'] = $value->vsuppliercode;
                        } else {
                            $temp[$m] = $value->$m;
                        }
                    }
                }
                $temp['vcompanyname'] = $value->vcompanyname;
                $temp['vdepartmentname'] = $value->vdepartmentname;
                $temp['vcategoryname'] = $value->vcategoryname;
                $temp['subcat_name'] = $value->subcat_name;
                $temp['nsaleprice'] = $value->nsaleprice;
                $temp['iqtyonhand'] = $value->iqtyonhand;
                // $temp['costtotal'] = number_format(($value['iqtyonhand'] * $value['unitcost']), 2);
                // $temp['pricetotal'] = number_format(($value['iqtyonhand'] * $value['dunitprice']), 2);

                $datas[] = $temp;
            }
        }

        $return = [];
        $return['draw'] = (int)$input['draw'];
        $return['recordsTotal'] = $count_total;
        $return['recordsFiltered'] = $count_records;
        $return['data'] = $datas;

        return response(json_encode($return), 200)
            ->header('Content-Type', 'application/json');
    }

    public function get_categories_by_department(Request $request)
    {
        $input = $request->all();
        if (isset($input['dep_code'])  && $input['dep_code'] != "") {
            $all_departments = $input['dep_code'][0];
            $departments = array();
            for ($i = 0; $i < count($all_departments); $i++) {
                $departments[] = $all_departments[$i];
            }
            $categories = Category::whereIn('dept_code', $departments)->get()->toArray();
            $cat_list = "<option value='all'>All</option>";
            foreach ($categories as $category) {
                if (isset($category['vcategorycode'])) {
                    $cat_code = $category['vcategorycode'];
                    $cat_name = $category['vcategoryname'];
                    $cat_list .= "<option value=" . $cat_code . ">" . $cat_name . "</option>";
                }
            }
            echo $cat_list;
        }
    }

    public function get_subcat_by_categories(Request $request)
    {
        $input = $request->all();
        if (isset($input['category_code'])  && $input['category_code'] != "") {
            $all_categories = $input['category_code'][0];
            $categories = array();
            for ($i = 0; $i < count($all_categories); $i++) {
                $categories[] = $all_categories[$i];
            }
            $subcategories = Subcategory::whereIn('cat_id', $categories)->get()->toArray();
            $subcat_list = "<option value='all'>All</option>";
            foreach($subcategories as $subcategory){
                if(isset($subcategory['subcat_name'])){
                    $sub_cat_id = $subcategory['subcat_id'];
                    $sub_cat_name = $subcategory['subcat_name'];
                    $subcat_list .= "<option value=".$sub_cat_id.">".$sub_cat_name."</option>";
                }
            }
            echo $subcat_list;
        }
    }

    public function create_session(Request $request){

        $input = $request->all();

        $selected_itemid = $input['itemid'];

        $selected_itemid = array_unique($selected_itemid);

        $selected_itemid = array_filter($selected_itemid);

        session()->forget('selected_itemid');
        session()->forget('scanned_selected_itemid');


        session()->put('selected_itemid', $selected_itemid);

        $data['item_id'] = $selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session created';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }


    public function create_scanned_session(Request $request){

        $input = $request->all();

        $scanned_selected_itemid = $input['itemid'];

        $scanned_selected_itemid = array_unique($scanned_selected_itemid);

        $scanned_selected_itemid = array_filter($scanned_selected_itemid);

        session()->forget('selected_itemid');
        session()->forget('scanned_selected_itemid');

        session()->put('scanned_selected_itemid', $scanned_selected_itemid);

        $data['item_id'] = $scanned_selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session created';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function get_scanned_data(){

        $json =array();

        $scanned_selected_itemid = session()->get('scanned_selected_itemid');

        $ids = join("','",$scanned_selected_itemid);

	    $select_query = "SELECT mi.iitemid, mi.npack, mi.vbarcode, mi.iqtyonhand, mi.vitemname, mi.vitemcode, (mi.dcostprice/mi.npack) as unitcost FROM mst_item as mi WHERE mi.iitemid IN ('".$ids."') ORDER BY mi.iitemid ";

        $itemdata = DB::connection('mysql_dynamic')->select($select_query);

        if(count($itemdata) > 0){
            $data['success'] = true;
            $data['scanned_data'] = $itemdata;
        }else{
            $data['success'] = false;
            $data['message'] = 'No Data Found';
        }

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
    }

    public function remove_session_scanned_data(Request $request){
        $input = $request->all();
	    $remove_scanned_itemid = $input['itemid'];
        $scanned_selected_itemid = session()->get('scanned_selected_itemid');
        session()->forget('scanned_selected_itemid');

	    foreach($remove_scanned_itemid as $itemid){
	        if (($key = array_search($itemid, $scanned_selected_itemid)) !== false) {
                unset($scanned_selected_itemid[$key]);
            }
        }

        session()->put('scanned_selected_itemid', $scanned_selected_itemid);
	    $data['item_id'] = $scanned_selected_itemid;

        $data['success'] = true;
        $data['success_msg'] = 'session Recreated';

        return response(json_encode($data), 200)
            ->header('Content-Type', 'application/json');
	}

    public function snapshot(Request $request)
    {
        $input = $request->all();

        if (isset($input['conditions']) && !empty($input['conditions']) && $input['conditions'] == 'all') {
            $all_itemid = DB::connection('mysql_connnection')->select("SELECT iitemid FROM mst_item as mi WHERE mi.visinventory = 'Yes' AND mi.estatus='Active' ");

            $selected_itemid = array();
            foreach ($all_itemid as $k => $itemid) {
                $selected_itemid[$k] = $itemid['iitemid'];
            }
            // dd($selected_itemid);
            // $this->load->model('administration/physical_inventory_detail');
            $result = PhysicalInventory::first()->snapshot($selected_itemid);
            if (isset($result)) {
                $this->get_details_of_selected_data($result);
                unset($selected_itemid);
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($this->session->data['scanned_selected_itemid']) && count($this->session->data['scanned_selected_itemid']) > 0 && $input['conditions'] == 'scanned_data') {
            // dd(count($this->session->data['scanned_selected_itemid']));
            $this->load->model('administration/physical_inventory_detail');

            $scanned_selected_itemid = $this->session->data['scanned_selected_itemid'];
            $ids = join("','", $scanned_selected_itemid);
            $result = $this->model_administration_physical_inventory_detail->snapshot($scanned_selected_itemid);

            if (isset($result)) {

                $this->get_details_of_selected_data($result);
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($this->session->data['selected_itemid']) && count($this->session->data['selected_itemid']) > 0 && $input['conditions'] == 'session_filters_data') {
            // dd(count($this->session->data['selected_itemid']));
            $this->load->model('administration/physical_inventory_detail');

            $selected_itemid = $this->session->data['selected_itemid'];
            $ids = join("','", $selected_itemid);
            $result = $this->model_administration_physical_inventory_detail->snapshot($selected_itemid);
            // dd($result);
            if (isset($result)) {

                $this->get_details_of_selected_data($result);
            } else {
                dd('Snapshot not taken');
            }
        } elseif (!empty($input['item_search']) || !empty($input['sku_search']) || !empty(trim($input['price_select_by'])) || isset($input['dept_code']) || isset($input['category_code']) || isset($input['subcat_id']) || isset($input['supplier_code'])) {

            // print_r($input['price_select_by']); dd($input['select_by_value_1']);
            $show_condition = "WHERE mi.visinventory = 'Yes' AND mi.estatus='Active'";
            $condition = "";

            if (isset($input['item_search']) && !empty(trim($input['item_search']))) {
                $search = $this->db->escape($input['item_search']);
                $condition .= " AND mi.vitemname LIKE  '%" . $search . "%'";
            }

            if (isset($input['sku_search']) && !empty(trim($input['sku_search']))) {
                $search = $this->db->escape($input['sku_search']);
                $condition .= " AND mi.vbarcode LIKE  '%" . $search . "%'";
            }

            if (isset($input['price_select_by']) && !empty(trim($input['price_select_by'])) && isset($input['select_by_value_1']) && !empty($input['select_by_value_1'])) {
                $price_select_by = $this->db->escape($input['price_select_by']);
                $select_by_value_1 = $this->db->escape($input['select_by_value_1']);
                $select_by_value_2 = $this->db->escape($input['select_by_value_2']);

                if (empty($select_by_value_1)) {
                    $select_by_value_1 = 0;
                }

                if ($price_select_by == 'greater') {
                    $condition .= " AND mi.dunitprice > $select_by_value_1 ";
                } elseif ($price_select_by == 'less') {
                    $condition .= " AND mi.dunitprice < $select_by_value_1 ";
                } elseif ($price_select_by == 'equal') {
                    $condition .= " AND mi.dunitprice = $select_by_value_1 ";
                } elseif ($price_select_by == 'between') {
                    if (empty($select_by_value_2)) {
                        $select_by_value_2 = 0;
                    }
                    $condition .= " AND mi.dunitprice BETWEEN $select_by_value_1 AND $select_by_value_2 ";
                }
            }

            if (isset($input['dept_code']) && count($input['dept_code']) > 0) {
                $search = $input['dept_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vdepcode IN ('" . ($search) . "')";
            }

            if (isset($input['category_code']) && count($input['category_code']) > 0) {
                $search = $input['category_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vcategorycode IN ('" . ($search) . "')";
            }

            if (isset($input['supplier_code']) && count($input['supplier_code']) > 0) {
                $search = $input['supplier_code'];
                $search = join("','", $search);
                $condition .= " AND mi.vsuppliercode IN ('" . ($search) . "')";
            }

            if (isset($input['subcat_id']) && count($input['subcat_id']) > 0) {
                $search = $input['subcat_id'];
                $search = join("','", $search);
                $condition .= " AND mi.subcat_id IN ('" . stripslashes($search) . "')";
            }

            // dd("SELECT iitemid FROM mst_item as mi ".$show_condition.$condition);
            $all_itemid = $this->db2->query("SELECT iitemid FROM mst_item as mi " . $show_condition . $condition)->rows;

            $selected_itemid = array();
            foreach ($all_itemid as $k => $itemid) {
                $selected_itemid[$k] = $itemid['iitemid'];
            }
            // dd($selected_itemid);
            $this->load->model('administration/physical_inventory_detail');
            $result = $this->model_administration_physical_inventory_detail->snapshot($selected_itemid);

            if (isset($result)) {

                $this->get_details_of_selected_data($result);
            } else {
                dd('Snapshot not taken');
            }
        } elseif (isset($this->session->data['ipiid'])) {

            $this->get_details_of_selected_data($this->session->data['ipiid']);
        } else {
            $this->response->redirect($this->url->link('administration/new_physical_inventory_detail', 'token=' . $this->session->data['token'] . $url, true));
        }
    }
}
