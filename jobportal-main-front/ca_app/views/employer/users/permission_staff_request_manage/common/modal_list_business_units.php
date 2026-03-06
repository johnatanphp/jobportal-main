<!-- Modal -->
<div id="modal-business-units" class="modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Unidad de negocios</h4>
            </div>
            <div class="modal-body">

               <table id="tbl-business-units" width="100%" class="table">
                    <thead>
                        <tr>
                            <th>

                                <button class="btn btn-xs btn-round btn-success business-units-selected-all" type="button">
                                    Agregar Todos
                                </button>
                            </th>
                            <th>Codigo</th>
                            <th>Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($business_units as $row): ?>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-xs btn-success add-business-unit">
                                        <i class="material-icons" style="font-size:10px;">add</i>
                                    </button>
                                </td>
                                <td><?php e($row->business_unit_code); ?></td>
                                <td><?php e($row->business_unit_name); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
               </table>
            </div>
        </div>
    </div>
</div>