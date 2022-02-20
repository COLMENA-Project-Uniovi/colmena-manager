<?php
/**
 * Form templates to show aux variables around inputs
 */
return [
    /**
     * Container element used by control().
     */
    'inputContainer' => '
        <div class="input {{type}}{{required}}">
            <div class="max" data-max="{{max}}">{{max}}</div><!-- .max -->
            <div class="help">
                <i class="far fa-question-circle"></i>
                <div class="info">
                    <div>{{help}}</div>
                </div><!-- .info -->
            </div><!-- .help -->
            {{content}}
        </div><!-- .input -->
    ',
    /**
     * Container element used by control() when a field has an error.
     */
    'inputContainerError' => '
        <div class="input {{type}}{{required}} error">
            <div class="max" data-max="{{max}}">{{max}}</div><!-- .max -->
            <div class="help">
                <i class="far fa-question-circle"></i>
                <div class="info">
                    <div>{{help}}</div>
                </div><!-- .info -->
            </div><!-- .help -->
            {{content}}
            {{error}}
        </div><!-- .input.error -->
    ',
    /**
     * Override dateWidget template so it uses the default html inputs instead
     * of the selects that the default widget populates in the form
     */
    'dateWidget' => '<input type="{{type}}" name="{{name}}" {{attrs}} />'
];
