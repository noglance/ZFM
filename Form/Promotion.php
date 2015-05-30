<?php

class Ladoga2_Form_Promotion extends Zend_Form {

    public function __construct($option = null) {
        parent::__construct($option);

        $this->setName('promotion');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Название:')
                ->setRequired(true)
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');


        $dist = new Zend_Form_Element_Select('iddist');
        $dist->setLabel('Торговая сеть')
                ->setRequired(true)
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');

        $manager = new Ladoga2_Model_User();
        $manager->id = Zend_Registry::get('uid');
        $order = array('name');
        $distributers = $manager->getManyDistributor(null, null, $order);
        foreach ($distributers as $distributer) {
            $dist->addMultiOptions(array($distributer->id => $distributer->name));
        }

        $start = new Zend_Form_Element_Text('start');
        $start->setLabel('Начало:')
                ->setAttribs(array('class' => 'in-date'))
                ->setRequired(true)
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors')
                ->addValidator(new Ladoga2_Form_Validator_PromotionDates())
                ->addValidator(new Ladoga2_Form_Validator_StartDate());

        $final = new Zend_Form_Element_Text('final');
        $final->setLabel('Окончание:')
                ->setAttribs(array('class' => 'in-date'))
                ->setRequired(true)
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Описание')
                ->setAttribs(array('rows' => 10, 'cols' => 30))
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');

        $comment = new Zend_Form_Element_Textarea('comment');
        $comment->setLabel('Комментарий')
                ->setAttribs(array('rows' => 10, 'cols' => 30))
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Создать')
                ->setAttribs(array('class' => 'form-button form-button-next'))
                ->removeDecorator('label')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('errors');

        if (isset($option['disabled'])) {
            if ($option['disabled']) {
                $name->setAttrib('disabled', 'disabled');
                $dist->setAttrib('disabled', 'disabled');
                $start->setAttrib('disabled', 'disabled');
                $final->setAttrib('disabled', 'disabled');
                $disabled = true;
            }
        }
        $this->addElements(array($name, $dist, $start, $final));

        $model = new Ladoga2_Model_Mpropertie();
        $mprops = $model->fetchAll();

        foreach ($mprops as $mp) {
            $element = new Zend_Form_Element_Checkbox("manyMpropertie_$mp->idmprop");
            $element->setLabel($mp->name)
                    ->setRequired(true)
                    ->removeDecorator('label')
                    ->removeDecorator('DtDdWrapper')
                    ->removeDecorator('htmlTag')
                    ->removeDecorator('errors');
            if ($disabled) {
                $element->setAttrib('disabled', 'disabled');
            }
            $this->addElement($element);
        }

        if (isset($option['idpromo'])) {
            $promotion = new Ladoga2_Model_Promotion();
            $promotion->id = $option['idpromo'];

            $idpromo = new Zend_Form_Element_Hidden('idpromo');
            $idpromo->removeDecorator('label')
                    ->removeDecorator('DtDdWrapper')
                    ->removeDecorator('htmlTag')
                    ->removeDecorator('errors')
                    ->setValue($option['idpromo']);
            $this->addElement($idpromo);

            $name->setValue($promotion->name);
            $dist->setValue($promotion->iddist);
            $dist->setAttrib('disabled', 'disabled');
            $dist->setRequired(false);
            $start->setValue(date("d.m.Y", strtotime($promotion->start)));
            $final->setValue(date("d.m.Y", strtotime($promotion->final)));
            $description->setValue($promotion->description);
            $comment->setValue($promotion->comment);
            $submit->setLabel('Сохранить изменения');

            $relMprop = $promotion->getManyMpropertie();
            foreach ($relMprop as $rel) {
                $element = $this->getElement("manyMpropertie_$rel->idmprop");
                $element->setValue(true);
            }

            if (!$disabled) {
                $remove = new Zend_Form_Element_Submit('remove');
                $remove->setLabel('Удалить')
                        ->setAttribs(array('class' => 'form-button form-button-next'))
                        ->removeDecorator('label')
                        ->removeDecorator('DtDdWrapper')
                        ->removeDecorator('htmlTag')
                        ->removeDecorator('errors')
                        ->setAttrib('onClick', 'remove();');
                $this->addElements(array($remove));
            }
        }


        if ($disabled) {
            $description->setAttrib('disabled', 'disabled');
            $comment->setAttrib('disabled', 'disabled');
        }
        $this->addElements(array($description, $comment));
        if (!$disabled) {
            $this->addElements(array($submit));
        }
        $this->setMethod('post');

        $csrf = new Zend_Form_Element_Text('csrf');
        $this->addElement(
                'hash', 'csrf', array(
            'ignore' => true,
                )
        );
        $this->csrf->removeDecorator('DtDdWrapper')
                ->removeDecorator('htmlTag')
                ->removeDecorator('label');
    }

}