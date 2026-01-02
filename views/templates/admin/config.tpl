<div class="panel">
    <h3>{l s='RAPYD MNEE Extension Configuration' mod='rapydmnee'}</h3>

    {* Add a link to visit merchant signup page *}
    <p style="margin-bottom:15px;">
        <a href="http://localhost:8082" target="_blank" class="btn btn-link p-0">
            {l s='Create Rapyd MNEE account to get an api-key' mod='rapydmnee'}
        </a>
    </p><hr>


    <form method="post" action="">
        <div class="form-group">
            <label for="api_key">
                {l s='Merchant API Key' mod='rapydmnee'}
            </label>

            <input
                type="text"
                id="api_key"
                name="RAPYDMNEE_API_KEY"
                value="{$api_key|escape:'html'}"
                class="form-control"
                required
            />
        </div>

        <button
            type="submit"
            name="submitRapydmneeConfig"
            class="btn btn-primary"
        >
            {l s='Save' mod='rapydmnee'}
        </button>
    </form>
</div>
