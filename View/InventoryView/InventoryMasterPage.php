<!-- Author name: Lim Shuye -->




<div class="header">

    <nav class="inventoryNav">
        <ul>
            <li class="dropdown <?php echo ($activePage == 'Dashboard') ? 'active' : ''; ?>">
                <a href="?controller=inventory&action=index" class="dropdown-toggle" data-toggle="dropdown">Dashboard <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="?controller=inventory&amp;action=index">Dashboard</a></li>
                </ul>
            </li>
            <li class="dropdown <?php echo ($activePage == 'Inventory Management') ? 'active' : ''; ?>">
                <a href="?controller=inventory&action=inventoryTracking" class="dropdown-toggle" data-toggle="dropdown">Inventory Management <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="dropdown"><a href="?controller=inventory&amp;action=habitatItem">Habitat Items</a>
                        <ul class="dropdown-submenu">
                            <li><a href="?controller=inventory&action=habitatItem">View All</a></li>
                            <li><a href="?controller=inventory&action=generateReport&report=habitatinventoryRecordReport">Generate Report</a></li>
                        </ul>
                    </li>
                    <li><a href="?controller=inventory&amp;action=foodItem">Food Inventory</a>
                        <ul class="dropdown-submenu">
                            <li><a href="?controller=inventory&action=foodItem">View All</a></li>
                            <li><a href="?controller=inventory&action=generateReport&report=foodinventoryRecordReport">Generate Report</a></li>
                        </ul>
                    </li>
                    <li><a href="?controller=inventory&amp;action=cleaningItem">Cleaning Inventory</a>
                        <ul class="dropdown-submenu">
                            <li><a href="?controller=inventory&action=cleaningItem">View All</a></li>
                            <li><a href="?controller=inventory&action=generateReport&report=cleaninginventoryRecordReport">Generate Report</a></li>
                        </ul>
                    </li>
                    <li><a href="?controller=animal&action=anilist">Animal Inventory</a>
                        <ul class="dropdown-submenu">
                            <li><a href="?controller=animal&action=anilist">View All</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown <?php echo ($activePage == 'Log Usage') ? 'active' : ''; ?>">
                <a href="?controller=inventory&action=logusage" class="dropdown-toggle" data-toggle="dropdown">Log Usage <b class="caret"></b></a>
            </li>
            <li class="dropdown <?php echo ($activePage == 'Purchase Order Management') ? 'active' : ''; ?>">
                <a href="?controller=inventory&action=showPO" class="dropdown-toggle" data-toggle="dropdown">Purchase Order Management <b class="caret"></b></a>
                <!--                        <ul class="dropdown-menu">
                                            <li><a href="create-new-order.php">Create New Order</a></li>
                                            <li><a href="view-all-orders.php">View All Orders</a></li>
                                            <li><a href="order-history.php">Order History</a></li>
                                            <li><a href="supplier-management.php">Supplier Management</a></li>
                                        </ul>-->
            </li>
            <li class="dropdown <?php echo ($activePage == 'Reports') ? 'active' : ''; ?>">
                <a href="?controller=inventory&action=reportMain&report=inventorySummaryReport" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="?controller=inventory&action=generateReport&report=inventorySummaryReport">Inventory Summary Report</a></li>
                    <li><a href="?controller=inventory&action=generateReport&report=cleaninginventoryRecordReport">Cleaning Inventory Summary Report</a></li>
                    <li><a href="?controller=inventory&action=generateReport&report=habitatinventoryRecordReport">Habitat Inventory Summary Report</a></li>
                    <li><a href="?controller=inventory&action=generateReport&report=foodinventoryRecordReport">Food Inventory Summary Report</a></li>
                    <li><a href="?controller=inventory&action=generateReport&report=poSummaryReport">Purchase Order Summary Report</a></li>
                </ul>
            </li>

        </ul>
    </nav>
    <h2> <?php echo $activePage; ?></h2>
</div>
<main>
    <?php
    if (!$xslt_transform) {
        echo $content;
    }
    ?>
</main>

<!--    </body>
</html>-->

<script>
    $(document).ready(function () {
        $('.dropdown-toggle').click(function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('open');
        });
    });
</script>
