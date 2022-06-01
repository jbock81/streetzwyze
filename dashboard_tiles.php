              
                        <div class="row d-none d-sm-flex title-bar">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; <span id="spval0"></span></h5>
                                        </div>
                                        <div class="float-right"><img id="img_dh0" src="//placehold.it/50x50" width="50px" height="50px" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; <span id="spval1"></span></h5>
                                        </div>
                                        <div class="float-right"><img id="img_dh1" src="//placehold.it/50x50" width="50px" height="50px" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; <span id="spval2"></span></h5>
                                        </div>
                                        <div class="float-right"><img id="img_dh2" src="//placehold.it/50x50" width="50px" height="50px" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-left">
                                            <h5 class="card-title">&#8358; <span id="spval3"></span></h5>
                                        </div>
                                        <div class="float-right"><img id="img_dh3" src="//placehold.it/50x50" width="50px" height="50px" class="img-responsive"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
<script language="javascript">
    var penalty_status = 0;
    var order_amount = 0;
    var reservation_id = "";
    var due_date = "";
    var m_id = 0;
    var g_obj = null;
    function get_dashboards()
    {
        $.post(
            "admin-seller/ajax/getdashboard_tiles.php",
            {
            },
            function(data){
                var obj_data = JSON.parse(data);
                g_obj = obj_data;
                penalty_status = obj_data.flags.dh0;
                order_amount = obj_data.orderAmount;
                reservation_id = obj_data.reservationID;
                due_date = obj_data.dueDate;
                m_id = obj_data.MId;
                $("#img_dh0").attr("src", "/img/dashboard/" + obj_data.imgs.dh0);
                $("#img_dh1").attr("src", "/img/dashboard/" + obj_data.imgs.dh1);
                $("#img_dh2").attr("src", "/img/dashboard/" + obj_data.imgs.dh2);
                $("#img_dh3").attr("src", "/img/dashboard/" + obj_data.imgs.dh3);
                $("#spval0").text(eval(g_obj.values.dh0));
                $("#spval1").text(eval(g_obj.values.dh1));
                $("#spval2").text(eval(g_obj.values.dh2));
                $("#spval3").text(eval(g_obj.values.dh3));
            }
        );
    }
    $(document).ready(function(){
        get_dashboards();
    });
    //window.setTimeout("get_dashboards()", 300000);
</script>