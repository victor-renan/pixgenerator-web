<head>
    <?php require_once 'templates/meta.php' ?>
    <title>Pix Generator</title>
</head>

<main id="app" class="is-flex is-justify-content-center is-align-items-center">
    <div class="container is-max-tablet">
        <div class="content has-text-centered">
            <h1>Gerador de QR Pix</h1>
            <p>Preencha os dados abaixo para gerar seu QRCode</p>
        </div>
        <div>
            <form>
                <div class="columns">
                    <div class="column is-full">
                        <div class="field">
                            <label class="label">Chave Pix <strong class="has-text-danger">*</strong></label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input id="input_pix-type" class="input" type="text" placeholder="">
                                    <span class="icon is-small is-left">
                                        <i class="bx bx-key"></i>
                                    </span>
                                </div>
                                <div class="control">
                                    <div class="select">
                                        <select id="select_pix-type"></select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="columns">
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Quantidade (R$)</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="0,00">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-dollar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Identificador da Transação</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="Palavra de identificação">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-id-card"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Nome</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="Digite seu nome">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Cidade</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="Digite o nome de sua cidade">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-buildings"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-full">
                        <div class="field">
                            <label class="label">Mensagem Adicional</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" placeholder="Escreva uma mensagem em poucas palavras">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-message"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="button is-primary is-fullwidth">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script defer>

const  { Mask, MaskInput } = Maska;

const pixTypeSelect = document.querySelector('#select_pix-type')
const pixTypeInput = document.querySelector('#input_pix-type')

const pixTypes = [
    { 
        value: 'cpfpj',
        display: 'CPF/CNPJ',
        mask: () => {
            new MaskInput(pixTypeInput, {mask: ['###.###.###-##', '##.###.###/####-##']})
        }
    },
    { 
        value: 'email',
        display: 'Email',
        mask: () => {}
    },
    { 
        value: 'phone',
        display: 'Telefone',
        mask: () => {}
    },
    { 
        value: 'random',
        display: 'Aleatória',
        mask: () => {}
    },
];

pixTypes.forEach((item) => {
    const option = document.createElement('option')
    option.value = item.value
    option.textContent = item.display
    pixTypeSelect.appendChild(option)
})

</script>

<style>
    #app {
        height: 100vh;
    }
</style>