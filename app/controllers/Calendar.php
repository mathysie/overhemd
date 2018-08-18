<?php

declare(strict_types=1);

namespace app\controllers;

use foulard\calendar\events\AanvraagEvent;
use foulard\datetime\FoulardDateTime;
use mako\http\response\senders\Redirect;

class Calendar extends BaseController
{
    public function getOverzicht(): string
    {
        $start = $this->getStart();
        $einde = $this->getEinde($start);
        $events = $this->calendarHelper->getEvents(
            $start,
            $einde,
            [],
            AanvraagEvent::class
        );
        $view = $this->view->create('calendar.overzicht');

        $view->assign('start', $start);
        $view->assign('einde', $einde);
        $view->assign('events', $events);

        return $view->render();
    }

    public function bewerkAanvraag(string $id): string
    {
        $aanvraag_event = $this->calendarHelper->getAanvraagEvent($id);

        $view = $this->view->create('calendar.bewerk');
        $view->assign('aanvraag_event', $aanvraag_event);

        $this->getFieldErrors($view);
        $this->getSuccess($view);

        return $view->render();
    }

    public function updateAanvraag(string $id): Redirect
    {
        $aanvraag_event = $this->calendarHelper->getAanvraagEvent($id);

        $aanvraag_event->startdatum = $_POST['startdatum'];
        $aanvraag_event->starttijd = $_POST['starttijd'];
        $aanvraag_event->einddatum = $_POST['einddatum'];
        $aanvraag_event->eindtijd = $_POST['eindtijd'];
        $aanvraag_event->tappers = explode(', ', $_POST['tappers']);
        $aanvraag_event->tap_min = (int) $_POST['tap_min'];

        if (!empty($_POST['startdatum']) && !empty($_POST['starttijd'])) {
            $aanvraag_event->start = new FoulardDateTime(
                sprintf('%s %s', $_POST['startdatum'], $_POST['starttijd'])
            );
        } else {
            $aanvraag_event->start = null;
        }
        if (!empty($_POST['einddatum']) && !empty($_POST['eindtijd'])) {
            $aanvraag_event->eind = new FoulardDateTime(
                sprintf('%s %s', $_POST['einddatum'], $_POST['eindtijd'])
            );
        } else {
            $aanvraag_event->eind = null;
        }

        for ($i = 0; $i < count($aanvraag_event->aanvragen); ++$i) {
            $aanvraag = $aanvraag_event->aanvragen[$i];

            // Als summary of description gewijzigd worden, dan kan het type
            // aanvraag anders zijn.
            if ($aanvraag->summary != $_POST["summary-{$i}"]
                || $aanvraag->description != $_POST["description-{$i}"]) {
                $aanvraag = $this->calendarParser->parseAanvraag(
                    $_POST["summary-{$i}"],
                    $_POST["description-{$i}"],
                    false
                );
            }
            $aanvraag->kwn = (bool) $_POST["kwn-{$i}"];
            $aanvraag->kwn_port = (int) $_POST["kwn-port-{$i}"];
            $aanvraag->contactpersoon = $_POST["contactpersoon-{$i}"];
            $aanvraag->setSAP($_POST["sap-{$i}"]);
            $aanvraag->pers = (int) $_POST["pers-{$i}"];

            $aanvraag_event->aanvragen[$i] = $aanvraag;
        }

        $aanvraag_event->isValid($this->validator, $errors);
        foreach ($aanvraag_event->aanvragen as $key => $aanvraag) {
            $errors_aanvraag = [];
            $aanvraag->isValid($this->validator, $errors_aanvraag);
            if (!empty($errors_aanvraag)) {
                $errors[$key] = $errors_aanvraag;
            }
        }

        if (!empty($errors)) {
            $this->passFieldErrors($errors);

            return $this->redirectResponse(
                'calendar.bewerk.aanvraag',
                ['id' => $id]
            );
        }

        $aanvraag_event->update();

        $this->passSuccess();

        return $this->redirectResponse(
            'calendar.bewerk.aanvraag',
            ['id' => $id]
        );
    }

    protected function getStart(): FoulardDateTime
    {
        if (isset($_GET['start'])) {
            return new FoulardDateTime($_GET['start']);
        } else {
            return new FoulardDateTime();
        }
    }

    protected function getEinde(FoulardDateTime $start): FoulardDateTime
    {
        if (isset($_GET['einde'])) {
            return new FoulardDateTime($_GET['einde']);
        } else {
            return new FoulardDateTime("{$start} + 2 months");
        }
    }
}
