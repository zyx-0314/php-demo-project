<?php

include_once TEMPLATES_PATH . "/footer.component.php";

function footer(array $pageJs = [])
{
    footerComponent();
    ?>
    </main>

    <?php
    if (!empty($pageJs)) {
        foreach ($pageJs as $jsFile) {
            echo "<script src=\"{$jsFile}\"></script>\n";
        }
    }
    ?>

    </body>

    </html>
    <?php
}
?>