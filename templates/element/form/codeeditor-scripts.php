<!-- Code Mirror configuration -->
<?= $this->Html->script(
    'vendors/codemirror/codemirror'
); ?>
    <!-- Syntax config -->
<?= $this->Html->script(
    'vendors/codemirror/addon/selection/active-line'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/edit/trailingspace'
); ?>
<style type="text/css">
    .cm-trailingspace {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAACCAYAAAB/qH1jAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QUXCToH00Y1UgAAACFJREFUCNdjPMDBUc/AwNDAAAFMTAwMDA0OP34wQgX/AQBYgwYEx4f9lQAAAABJRU5ErkJggg==);
        background-position: bottom left;
        background-repeat: repeat-x;
    }
</style>
    <!-- Display -->
<?= $this->Html->script(
    'vendors/codemirror/addon/display/autorefresh'
); ?>
    <!-- HTML & CSS config -->
<?= $this->Html->script(
    'vendors/codemirror/addon/edit/matchtags'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/edit/closetag'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/fold/xml-fold'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/hint/show-hint'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/hint/xml-hint'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/hint/html-hint'
); ?>
    <!-- Error reporting -->
<?= $this->Html->script(
    'vendors/codemirror/addon/lint/lint'
); ?>
<?= $this->Html->script(
    'https://cdn.jsdelivr.net/npm/csslint@1.0.5/dist/csslint.js'
); ?>
<?= $this->Html->script(
    'https://cdn.jsdelivr.net/npm/jshint@2.10.1/dist/jshint.js'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/lint/css-lint'
); ?>
<?= $this->Html->script(
    'https://cdn.jsdelivr.net/npm/htmlhint@latest/dist/htmlhint.js'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/addon/lint/html-lint'
); ?>
    <!-- Supported languages -->
<?= $this->Html->script(
    'vendors/codemirror/mode/xml'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/mode/javascript'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/mode/css'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/mode/htmlmixed'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/mode/php'
); ?>
<?= $this->Html->script(
    'vendors/codemirror/mode/apache'
); ?>
