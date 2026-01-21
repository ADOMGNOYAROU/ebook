<?php

namespace App\View\Components;

use App\Models\Ebook;
use Illuminate\View\Component;

class EbookCard extends Component
{
    /**
     * L'ebook à afficher
     *
     * @var \App\Models\Ebook
     */
    public $ebook;

    /**
     * Créer une nouvelle instance du composant.
     *
     * @param  \App\Models\Ebook  $ebook
     * @return void
     */
    public function __construct(Ebook $ebook)
    {
        $this->ebook = $ebook;
    }

    /**
     * Récupérer la vue / le contenu qui représente le composant.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ebook-card');
    }
}
