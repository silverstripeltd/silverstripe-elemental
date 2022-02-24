<?php

namespace DNADesign\Elemental\Forms;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\TreeDropdownField;

class MoveElementHandler
{
    use Injectable;

    /**
     * Parent controller for this form
     *
     * @var Controller
     */
    protected $controller;

    public function __construct($controller = null)
    {
        $this->controller = $controller;
    }

    public function Form($elementID)
    {
        $fields = FieldList::create([
            HiddenField::create(
                'ElementID',
                null,
                $elementID
            ),
            $pageField = TreeDropdownField::create(
                'PageID',
                'Select a page',
                SiteTree::class
            )
        ]);
        $actions = FieldList::create([
            FormAction::create('moveelement', 'Move')
                ->addExtraClass('btn btn-primary')
        ]);

        $pageField->setSearchFunction(function ($sourceObject, $labelField, $search) {
            return DataObject::get($sourceObject)
                ->filterAny([
                    'MenuTitle:PartialMatch' => $search,
                    'Title:PartialMatch' => $search,
                ]);
        });

        $form = Form::create(
            $this->controller,
            sprintf('MoveElementForm_%s', $elementID),
            $fields,
            $actions
        );

        // Todo: make this dynamic
        $form->setFormAction('admin/elemental-area/MoveElementForm/');
        $form->addExtraClass('form--no-dividers');

        return $form;
    }
}
