    <?php if(!empty($table)){ ?>
    <div class="container">
        <table border="0" class="smsnf-table">
            <thead>
            <tr>
                <th><?=__('ID','egoi-for-wp');?></th>
                <th><?=__('Name','egoi-for-wp');?></th>
                <th><?=__('Language','egoi-for-wp');?></th>
                <th><?=__('Currency','egoi-for-wp');?></th>
                <th><?=__('Status','egoi-for-wp');?></th>
                <th><?=__('Operations','egoi-for-wp');?></th>
                <th><?=__('Created At','egoi-for-wp');?></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($table as $catalog){ echo EgoiProductsBo::genTableCatalog($catalog); } ?>
            </tbody>
        </table>
    <?php }else{ ?>
    <div class="container" style="display: flex;justify-content: center;align-items: center;flex-direction: column;min-height: 100%;">
        <div style="text-align: center;">
            <h2><?=__('There are no Catalogs!','egoi-for-wp');?></h2>
            <h4><?=__('Create your first catalog','egoi-for-wp');?></h4>
        </div>
    <?php } ?>
    <div class="egoi-undertable-button-wrapper" style="bottom: 0;position: absolute;right: 30px;">
        <div class="smsnf-input-group">
            <input type="submit" id="new_catalog_page" value="<?php _e('New Catalog', 'egoi-for-wp');?>" />
        </div>
    </div>
</div>


<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="egoi-modal-header modal-header">
                <button type="button" class="close no-border-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="modal-title" id="modalLabel"><?=__('Import Catalog: ','egoi-for-wp');?> <span id="display-selected"></span></h2>
            </div>
            <div class="modal-body nav">
                <input type="hidden" id="selected-import-catalog">
                <span><?=__('This will import all your store\'s products!','egoi-for-wp');?></span>
                <span><?=__('After this all new products will be synchronized to the selected catalog.','egoi-for-wp');?></span>
                <div style="display: flex;"><h4><?=__('Products to import: ','egoi-for-wp');?> <span id="display-number-products"></span></h4><?=getLoader('egoi-loader-products',false)?></div>
                <div id="loading-import" style="display: none;">
                    <span>Products left: <span id="egoi-left-products"></span></span>
                    <div class="progress">
                        <div id="progressbar-import" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="start-import-catalog" type="button" class="button-primary" disabled><?=__('Start','egoi-for-wp');?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="egoi-modal-header modal-header">
                <button type="button" class="close no-border-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="modal-title" id="modalLabel"><?=__('Delete','egoi-for-wp');?></h2>
            </div>
            <div class="modal-body nav">
                <input type="hidden" id="selected-delete-catalog">
                <span><?=__('All imported products will be removed from E-goi if you delete this catalog!','egoi-for-wp');?></span>
                <div style="display: flex;"><h4><?=__('Are you sure?','egoi-for-wp');?></h4></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><?=__('Cancel','egoi-for-wp');?></span>
                </button>
                <button id="verified-delete-catalog" type="button" class="button egoi-remove-button" data-dismiss="modal">
                    <span aria-hidden="true"><?=__('Yes','egoi-for-wp');?></span>
                </button>
            </div>
        </div>
    </div>
</div>