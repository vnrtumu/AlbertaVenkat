<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PhysicalInventory extends Model
{
    protected $connection = 'mysql_dynamic';
    protected $table = 'trn_physicalinventory';
    public $timestamps = false;
    protected $fillable = [
        'ipiid',
        'vpinvtnumber',
        'vrefnumber',
        'nnettotal',
        'ntaxtotal',
        'dcreatedate',
        'estatus',
        'vordertitle',
        'vnotes',
        'dlastupdate',
        'vtype',
        'ilocid',
        'dcalculatedate',
        'dclosedate',
        'LastUpdate',
        'SID'
    ];


    public function getItemsResult($itemdata = array())
    {
        $datas = array();
        $sql_string = '';
        if (isset($itemdata['searchbox']) && !empty($itemdata['searchbox'])) {
            $sql_string .= " WHERE a.iitemid= " . (int)$this->db->escape($itemdata['searchbox']);
            if (isset($itemdata['sort_items']) && $itemdata['sort_items'] != '') {
                $sql_string .= ' ORDER BY a.vitemname ' . $itemdata['sort_items'];
            } else {
                $sql_string .= ' ORDER BY a.LastUpdate DESC';
            }
        } else {

            if (isset($itemdata['sort_items']) && $itemdata['sort_items'] != '') {
                $sql_string .= ' WHERE a.estatus="' . $itemdata['show_items'] . '" ORDER BY a.vitemname ' . $itemdata['sort_items'];
            } else {
                $sql_string .= ' WHERE a.estatus="' . $itemdata['show_items'] . '" ORDER BY a.LastUpdate DESC';
            }

            if (isset($itemdata['start']) || isset($itemdata['limit'])) {
                if ($itemdata['start'] < 0) {
                    $itemdata['start'] = 0;
                }

                if ($itemdata['limit'] < 1) {
                    $itemdata['limit'] = 20;
                }

                $sql_string .= " LIMIT " . (int)$itemdata['start'] . "," . (int)$itemdata['limit'];
            }
        }

        $itemListings = array("vdepcode" => "Department", "vcategorycode" => "Category", "vsuppliercode" => "Supplier", "subcat_id" => "Sub Category", "iqtyonhand" => "Qty. on Hand");

        if (count($itemListings) > 0) {

            $fetch_field_sql = '';
            $fetch_table_sql = '';
            $sql_match = '';

            foreach ($itemListings as $key => $temp_itemListing) {
                if ($key == 'vdepcode') {
                    $fetch_field_sql .= 'a.vdepcode,a.dunitprice,md.vdepartmentname,';
                    $fetch_table_sql .= ' LEFT JOIN mst_department as md ON(a.vdepcode=md.vdepcode)';
                } else if ($key == 'vcategorycode') {
                    $fetch_field_sql .= 'a.vcategorycode,mc.vcategoryname,';
                    $fetch_table_sql .= ' LEFT JOIN mst_category as mc ON(a.vcategorycode=mc.vcategorycode)';
                } else if ($key == 'subcat_id') {
                    $fetch_field_sql .= 'a.subcat_id,msc.subcat_name,';
                    $fetch_table_sql .= ' LEFT JOIN mst_subcategory as msc ON(a.subcat_id=msc.subcat_id)';
                } else if ($key == 'vsuppliercode') {
                    $fetch_field_sql .= 'a.vsuppliercode,ms.vcompanyname,';
                    $fetch_table_sql .= ' LEFT JOIN mst_supplier as ms ON(a.vsuppliercode=ms.vsuppliercode)';
                } else {
                    $fetch_field_sql .= 'a.' . $key . ',';
                }
            }

            $fetch_table_sql = rtrim($fetch_table_sql, ", ");

            $query = DB::connection('mysql_dynamic')->select("SELECT $fetch_field_sql CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, case isparentchild when 0 then a.VITEMNAME  when 1 then Concat(a.VITEMNAME,' [Child]') when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME, a.iitemid,a.vitemtype,a.vbarcode,a.isparentchild FROM mst_item a $fetch_table_sql $sql_string ");

            return $query;
        } else {

            $query = DB::connection('mysql_dynamic')->select("SELECT a.iitemid,a.vitemtype,a.vitemname,a.vbarcode,a.vcategorycode, mc.vcategoryname,a.vdepcode,md.vdepartmentname,a.vsuppliercode,ms.vcompanyname,a.iqtyonhand,a.dunitprice,a.isparentchild, a.subcat_id,msc.subcat_name, CASE WHEN a.NPACK = 1 or (a.npack is null)   then a.IQTYONHAND else (Concat(cast(((a.IQTYONHAND div a.NPACK )) as signed), '  (', Mod(a.IQTYONHAND,a.NPACK) ,')') ) end as IQTYONHAND, case isparentchild when 0 then a.VITEMNAME  when 1 then Concat(a.VITEMNAME,' [Child]') when 2 then  Concat(a.VITEMNAME,' [Parent]') end   as VITEMNAME FROM mst_item as a LEFT JOIN mst_category as mc ON (a.vcategorycode=mc.vcategorycode) LEFT JOIN mst_department as md ON(a.vdepcode=md.vdepcode) LEFT JOIN mst_subcategory as msc ON(a.subcat_id=msc.subcat_id) LEFT JOIN mst_supplier as ms ON (a.vsuppliercode=ms.vsuppliercode) $sql_string ");

            return $query;
        }
    }

    public function getTotalItems($data = array())
    {
        $sql = "SELECT COUNT(*) AS total FROM mst_item ";

        if (!empty($data['searchbox'])) {
            $sql .= " WHERE iitemid= " . $data['searchbox'];
        } else {
            $sql .= " WHERE estatus= '" . $data['show_items'] . "'";
        }

        $query = DB::connection('mysql_dynamic')->select($sql);

        return $query;
    }

    public function snapshot($selected_itemid)
    {
        $last_refno = DB::connection('mysql_dynamic')->select("SELECT vrefnumber FROM trn_physicalinventory ORDER BY ipiid DESC LIMIT 1");

        if (isset($last_refno) && !empty($last_refno)) {
            $vrefnumber = $last_refno['vrefnumber'] + 1;
            $vrefnumber = str_pad($vrefnumber, 8, '0', STR_PAD_LEFT);
        } else {
            $vrefnumber = 1;
            $vrefnumber = str_pad($vrefnumber, 8, '0', STR_PAD_LEFT);
        }

        $dcreatedate = date("Y-m-d H:i:s");

        $query_insert = "INSERT INTO trn_physicalinventory SET  vrefnumber = '" . $vrefnumber . "', dcreatedate = '" . $dcreatedate . "', estatus = 'Open', vtype = 'Physical', SID = '" . (int)($this->session->data['sid']) . "' ";
        $insert  = DB::connection('mysql_dynamic')->select($query_insert);
        dd($insert);
        // $ipiid = $this->db2->getLastId();
        // $ipiid = $insert->lastInsertId();

        if ($insert == true) {
            foreach ($selected_itemid as $itemid) {

                $itemdata = DB::connection('mysql_dynamic')->select("select vbarcode, iqtyonhand, npack from mst_item where iitemid = '" . $itemid . "'");
                $this->check_table_mst_physical_inventory_snapshot();

                $insert_item_query = "INSERT INTO mst_physical_inventory_snapshot SET  ipiid = '" . $ipiid . "', iitemid = '" . $this->db->escape($itemid) . "', vbarcode = '" . $this->db->escape($itemdata['vbarcode']) . "', iqtyonhand = '" . $this->db->escape($itemdata['iqtyonhand']) . "', npack = '" . $this->db->escape($itemdata['npack']) . "', created = '" . $dcreatedate . "' ";
                $insert  = DB::connection('mysql_dynamic')->select($insert_item_query);
            }

            $text  = "Ref. number " . $vrefnumber . PHP_EOL .
                "No of snapshot items: " . count($selected_itemid) . PHP_EOL .
                "-------------------------" . PHP_EOL;

            // $logPhy = new Log('physical_inventory.log');

            // $logPhy->write($text);

            unset($this->session->data['selected_itemid']);
            unset($this->session->data['scanned_selected_itemid']);
            $this->session->data['ipiid'] = $ipiid;
            return $ipiid;
        }
    }
}
