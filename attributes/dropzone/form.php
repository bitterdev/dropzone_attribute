<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Attribute\Controller;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\Utility\Service\Identifier;

/** @var array|File[] $currentFiles */
if (!isset($currentFiles)) {
  $currentFiles = [];
}

/** @var Controller $view */
/** @var \Bitter\DropzoneAttribute\Entity\Attribute\Key\BrandKey $attributeKey */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Identifier $idHelper */
$idHelper = $app->make(Identifier::class);

$previewTemplateId = "ccm-" . $idHelper->getString() . "-file-upload-preview-template";
?>

<script type="text/template" id="<?php echo $previewTemplateId; ?>">
  <div class="ccm-file-upload-wrapper">
      <div class="ccm-file-upload-item-wrapper">
          <div class="ccm-file-upload-item">
              <div class="ccm-file-upload-item-inner">
                  <div class="ccm-file-upload-image-wrapper">
                      <img data-dz-thumbnail="">
                  </div>

                  <div class="ccm-file-upload-progress-text">
                      <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                          <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" class="ccm-file-upload-progress-text-value"></text>
                      </svg>
                  </div>

                  <svg viewbox="0 0 120 120" width="120" height="120" class="ccm-file-upload-progress">
                      <circle stroke="#4A90E2" stroke-width="5" fill="transparent" r="52" cx="60" cy="60"></circle>
                  </svg>
              </div>
          </div>

          <div class="ccm-file-upload-label dz-filename" data-dz-name></div>

          <input name="<?php echo $view->field('value') . "[]"; ?>" value="" type="hidden" />
      </div>
  </div>
</script>

<div class="ccm-file-upload-container-wrapper">
    <div class="ccm-file-upload ccm-file-upload-container <?php echo count($currentFiles) > 0 ? "dz-started" : ""; ?>" data-preview-element="<?php echo $previewTemplateId; ?>">
        <?php foreach($currentFiles as $file) { ?>
          <div class="ccm-file-upload-wrapper">
              <div class="ccm-file-upload-item-wrapper">
                  <div class="ccm-file-upload-item">
                      <div class="ccm-file-upload-item-inner">
                          <div class="ccm-file-upload-image-wrapper">
                              <img src="<?php echo $file->getThumbnailURL("file_manager_listing"); ?>">
                          </div>
                      </div>
                  </div>

                  <div class="ccm-file-upload-label">
                    <?php echo $file->getFilename(); ?>
                  </div>

                  <input name="<?php echo $view->field('value') . "[]"; ?>" value="<?php echo $file->getFileID(); ?>" type="hidden" />
              </div>
          </div>
        <?php } ?>

        <div class="dz-default dz-message">
            <button type="button" class="dz-button">
                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI0LjEuMiwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkViZW5lXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxMzIgMTMyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAxMzIgMTMyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+Cgkuc3Qwe29wYWNpdHk6MC45O2ZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO2ZpbGw6I0U2RjVGRjtlbmFibGUtYmFja2dyb3VuZDpuZXcgICAgO30KCS5zdDF7ZmlsbDojNEE5MEUyO30KCS5zdDJ7ZmlsbDpub25lO3N0cm9rZTojRThGNkZGO3N0cm9rZS13aWR0aDo4O30KPC9zdHlsZT4KPGcgaWQ9IlBhZ2UtMSI+Cgk8ZyBpZD0iRmlsZS1NYW5hZ2VyLS0tRmlsZVNldHMtRHJvcGRvd24iIHRyYW5zZm9ybT0idHJhbnNsYXRlKC02NTUuMDAwMDAwLCAtNjY3LjAwMDAwMCkiPgoJCTxyZWN0IGlkPSJSZWN0YW5nbGUiIHg9IjEiIHk9IjQ2OCIgY2xhc3M9InN0MCIgd2lkdGg9IjE0NDAiIGhlaWdodD0iNTQwIi8+CgkJPGcgaWQ9Imljb25zOC1kcmFnLWFuZC1kcm9wIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg2NTUuMDAwMDAwLCA2NjcuMDAwMDAwKSI+CgkJCTxwYXRoIGlkPSJTaGFwZSIgY2xhc3M9InN0MSIgZD0iTTMuMiwwYzAsMC0wLjEsMC0wLjEsMEMzLjEsMCwzLDAsMi45LDBDMi44LDAsMi43LDAuMSwyLjYsMC4xQzIuNCwwLjIsMi4xLDAuMiwxLjgsMC4zCgkJCQlDMS43LDAuNCwxLjUsMC41LDEuNCwwLjZDMS4yLDAuOCwxLDAuOSwwLjksMUMwLjgsMS4xLDAuNywxLjMsMC42LDEuNEMwLjUsMS42LDAuMywxLjgsMC4yLDIuMUMwLjIsMi4yLDAuMiwyLjQsMC4xLDIuNQoJCQkJQzAuMSwyLjcsMCwyLjksMCwzLjF2MTMuMWMwLDEuNywxLjQsMy4xLDMuMiwzLjFzMy4yLTEuNCwzLjItMy4xVjYuNGgxM2MxLjcsMCwzLjEtMS40LDMuMS0zLjJTMjEuMSwwLDE5LjQsMEgzLjUKCQkJCUMzLjQsMCwzLjQsMCwzLjIsMEMzLjMsMCwzLjIsMCwzLjIsMHogTTMyLjEsMEMzMC40LDAsMjksMS40LDI5LDMuMnMxLjQsMy4yLDMuMSwzLjJoNi42YzEuNywwLDMuMS0xLjQsMy4xLTMuMlM0MC40LDAsMzguNywwCgkJCQlIMzIuMXogTTUxLjQsMGMtMS43LDAtMy4xLDEuNC0zLjEsMy4yczEuNCwzLjIsMy4xLDMuMmg2LjZjMS43LDAsMy4xLTEuNCwzLjEtMy4yUzU5LjgsMCw1OC4xLDBINTEuNHogTTcwLjcsMAoJCQkJYy0xLjcsMC0zLjEsMS40LTMuMSwzLjJzMS40LDMuMiwzLjEsMy4yaDYuNmMxLjcsMCwzLjEtMS40LDMuMS0zLjJTNzkuMSwwLDc3LjQsMEg3MC43eiBNOTAsMGMtMS43LDAtMy4xLDEuNC0zLjEsMy4yCgkJCQlzMS40LDMuMiwzLjEsMy4yaDEzdjkuOGMwLDEuNywxLjQsMy4xLDMuMiwzLjFjMS44LDAsMy4yLTEuNCwzLjItMy4xVjMuMWMwLTAuMi0wLjEtMC40LTAuMS0wLjZjMC0wLjIsMC0wLjMtMC4xLTAuNQoJCQkJYy0wLjEtMC4yLTAuMi0wLjQtMC40LTAuN2MtMC4xLTAuMS0wLjItMC4zLTAuMy0wLjRjLTAuMi0wLjItMC4zLTAuMy0wLjUtMC40Yy0wLjItMC4xLTAuMy0wLjItMC41LTAuMwoJCQkJYy0wLjItMC4xLTAuNS0wLjEtMC44LTAuMmMtMC4xLDAtMC4yLTAuMS0wLjMtMC4xYy0wLjEsMC0wLjEsMC0wLjIsMGMwLDAtMC4xLDAtMC4xLDBjMCwwLDAsMC0wLjEsMGMtMC4xLDAtMC4xLDAtMC4yLDBIOTB6CgkJCQkgTTMuMiwyNS44Yy0xLjgsMC0zLjIsMS40LTMuMiwzLjF2Ni42YzAsMS43LDEuNCwzLjEsMy4yLDMuMXMzLjItMS40LDMuMi0zLjF2LTYuNkM2LjQsMjcuMiw1LDI1LjgsMy4yLDI1Ljh6IE0xMDYuMiwyNS44CgkJCQljLTEuOCwwLTMuMiwxLjQtMy4yLDMuMXY2LjZjMCwxLjcsMS40LDMuMSwzLjIsMy4xYzEuOCwwLDMuMi0xLjQsMy4yLTMuMXYtNi42QzEwOS41LDI3LjIsMTA4LDI1LjgsMTA2LjIsMjUuOHogTTMuMiw0NS4xCgkJCQljLTEuOCwwLTMuMiwxLjQtMy4yLDMuMXY2LjZDMCw1Ni41LDEuNCw1OCwzLjIsNThzMy4yLTEuNCwzLjItMy4xdi02LjZDNi40LDQ2LjUsNSw0NS4xLDMuMiw0NS4xeiBNNDEuOSw0NS4xCgkJCQlDMzQuOCw0NS4xLDI5LDUwLjksMjksNTh2NjEuMmMwLDcuMSw1LjgsMTIuOSwxMi45LDEyLjloNzcuM2M3LjEsMCwxMi45LTUuOCwxMi45LTEyLjlWNThjMC03LjEtNS44LTEyLjktMTIuOS0xMi45SDQxLjl6CgkJCQkgTTQxLjksNTEuNWg3Ny4zYzMuNiwwLDYuNCwyLjgsNi40LDYuNHY2MS4yYzAsMy42LTIuOCw2LjQtNi40LDYuNEg0MS45Yy0zLjYsMC02LjQtMi44LTYuNC02LjRWNTgKCQkJCUMzNS40LDU0LjQsMzguMyw1MS41LDQxLjksNTEuNXogTTMuMiw2NC40Yy0xLjgsMC0zLjIsMS40LTMuMiwzLjF2Ni42YzAsMS43LDEuNCwzLjEsMy4yLDMuMXMzLjItMS40LDMuMi0zLjF2LTYuNgoJCQkJQzYuNCw2NS44LDUsNjQuNCwzLjIsNjQuNHogTTc0LDcwLjhWMTAzbDcuOC02LjZsNC45LDExLjVsNC4xLTEuOGwtNS4yLTExLjNsMTAuOS0xLjRMNzQsNzAuOHogTTMuMiw4My43CgkJCQljLTEuOCwwLTMuMiwxLjQtMy4yLDMuMXYxMi43YzAsMC4xLDAsMC4xLDAsMC4yYzAsMCwwLDAsMCwwLjFjMCwwLDAsMC4xLDAsMC4xYzAsMC4xLDAsMC4xLDAsMC4yYzAsMC4xLDAuMSwwLjIsMC4xLDAuMwoJCQkJYzAuMSwwLjMsMC4xLDAuNSwwLjIsMC44YzAuMSwwLjIsMC4yLDAuMywwLjMsMC41YzAuMSwwLjIsMC4yLDAuNCwwLjQsMC41YzAuMSwwLjEsMC4zLDAuMiwwLjQsMC4zYzAuMiwwLjEsMC40LDAuMywwLjcsMC40CgkJCQljMC4xLDAuMSwwLjMsMC4xLDAuNSwwLjFjMC4yLDAsMC40LDAuMSwwLjYsMC4xaDE2LjNjMS43LDAsMy4xLTEuNCwzLjEtMy4ycy0xLjQtMy4yLTMuMS0zLjJoLTEzdi05LjhDNi40LDg1LjEsNSw4My43LDMuMiw4My43CgkJCQl6Ii8+CgkJPC9nPgoJCTxyZWN0IGlkPSJSZWN0YW5nbGUtQ29weS00IiB4PSI0IiB5PSIxNzciIGNsYXNzPSJzdDIiIHdpZHRoPSIxNDMyIiBoZWlnaHQ9IjgyOSIvPgoJPC9nPgo8L2c+Cjwvc3ZnPgo=" :alt="i18n.dropFilesHere">
                <span>

                <?php echo t("Drop files here or click to upload"); ?>
            </button>
        </div>

        <input type="file" class="ccm-file-upload-container-dropzone-file-element d-none" multiple="multiple">
    </div>
</div>

<?php 
    $attributeValue = $controller->getAttributeValueObject(); 
    $user = new User;

?>

<?php if ($attributeValue instanceof \Bitter\DropzoneAttribute\Entity\Attribute\Value\Value\DropzoneValue && $user->isSuperUser()) { ?>
    <div style="margin-top: 5px">
        <p class="text-muted">
            <?php echo t("Click %s to download all files.", sprintf(
                "<a href=\"%s\">%s</a>",
                Url::to("/api/v1/dropzone/download_files")->setQuery([
                    "avID" => (string)$attributeValue->getGenericValue()
                ]),
                t("here")
            )); ?>
        </p>
    </div>    
<?php } ?>
