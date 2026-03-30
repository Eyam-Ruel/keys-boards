<?php

class VueProfil extends VueBase
{
    public function __construct()
    {
        parent::__construct("Profile - LinkUp");
    }

    public function afficher()
    {
        $this->contenu = '
        <section class="kb-profile-page">
            <div class="kb-page-heading">
                <h1 class="kb-page-heading__title">My Profile</h1>
                <p class="kb-page-heading__subtitle">Manage your personal information and location.</p>
            </div>

            <div class="kb-profile-card">
                <form class="kb-profile-form" method="POST" action="index.php?action=saveProfileLocation">
                    <div class="kb-profile-form__group">
                        <label for="address-input" class="kb-profile-form__label">Address</label>
                        <input
                            type="text"
                            id="address-input"
                            name="address"
                            class="kb-profile-form__input"
                            placeholder="Start typing your address..."
                            autocomplete="off"
                        >
                        <div id="address-suggestions" class="kb-profile-form__suggestions"></div>
                    </div>

                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">

                    <button type="submit" class="kb-profile-form__button">Save Location</button>
                </form>
            </div>
        </section>';

        parent::afficher();
    }
}