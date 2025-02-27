<main id="app" class="is-flex is-justify-content-center is-align-items-center">
    <div class="container is-max-tablet">
        <div class="content has-text-centered">
            <i class="box is-bordered bx bx-qr is-size-1 p-1 mb-0"></i>
            <h1 class="mt-3">Gerador de QR Pix</h1>
            <p>Preencha os dados abaixo para gerar seu QRCode</p>
        </div>
        <div>
            <form id="form_pix">
                <div class="columns">
                    <div class="column is-full">
                        <div class="field">
                            <label class="label">Chave Pix <strong class="has-text-danger">*</strong></label>
                            <div class="field has-addons">
                                <div class="control has-icons-left is-expanded">
                                    <input name="pix-key" id="input_pix-type" class="input" type="text" required>
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
                            <label class="label">Quantidade</label>
                            <div class="control has-icons-left">
                                <input name="amount" id="input_pix-amount" class="input" type="text"
                                    placeholder="R$ 0,00">
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
                                <input name="transaction-id" class="input" type="text"
                                    placeholder="Palavra de identificação">
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
                                <input name="name" class="input" type="text" placeholder="Digite seu nome">
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
                                <input name="city" class="input" type="text" placeholder="Digite o nome de sua cidade">
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
                                <input name="additional-info" class="input" type="text"
                                    placeholder="Escreva uma mensagem em poucas palavras">
                                <span class="icon is-small is-left">
                                    <i class="bx bx-message"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="button is-primary is-fullwidth">
                        <i class="bx bx-qr mr-1"></i>
                        Gerar QR
                    </button>
                </div>
            </form>

        </div>
    </div>
    <div class="modal" id="modal_qr">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head is-shadowless">
                <p class="modal-card-title is-size-6 has-text-weight-bold">QR Code / Copia e Cola</p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body is-align-items-center">
                <div class="is-flex is-flex-direction-column is-align-items-center">
                    <img id="qr-image" alt="Imagem do QRCode" class="mb-3 mx-auto">
                </div>
            </section>
            <footer class="modal-card-foot">
                <div class="buttons">
                    <button id="qr-copy-code" class="button is-primary">
                        <i class="bx bx-copy mr-1"></i>
                        <span>Copiar Código Pix</span>
                    </button>
                </div>
            </footer>
        </div>
    </div>
</main>

<script defer>
    const { Mask, MaskInput } = Maska;

    const pixForm = document.querySelector('#form_pix')
    const pixTypeSelect = document.querySelector('#select_pix-type')
    const pixTypeInput = document.querySelector('#input_pix-type')
    const pixAmountInput = document.querySelector('#input_pix-amount')
    const qrModal = document.querySelector('#modal_qr')
    const qrImage = document.querySelector('#qr-image')
    const qrCopy = document.querySelector('#qr-copy-code')
    const pixTypes = {
        'cpf': {
            display: 'CPF',
            mask: () => {
                pixTypeInput.placeholder = '000.000.000-00'
                return '###.###.###-##'
            }
        },
        'cnpj': {
            display: 'CNPJ',
            mask: () => {
                pixTypeInput.placeholder = '00.000.000/0000-00'
                return '##.###.###/####-##'
            }
        },
        'phone': {
            display: 'Telefone',
            mask: () => {
                pixTypeInput.placeholder = '(00) 00000-0000'
                return '(##) #####-####'
            }
        },
        'email': {
            display: 'Email',
            mask: () => {
                pixTypeInput.type = 'email';
                pixTypeInput.placeholder = 'exemplo@mail.com'
            }
        },
        'random': {
            display: 'Aleatória',
            mask: () => {
                pixTypeInput.placeholder = 'Chave aleatória'
            }
        },
    }

    const createOption = (value, display, target) => {
        const option = document.createElement('option')
        option.value = value
        option.textContent = display
        target.appendChild(option)
        return option
    }

    Object.keys(pixTypes).forEach((item, i) => {
        createOption(item, pixTypes[item].display, pixTypeSelect)
        if (i == 0) {
            pixTypes[item].mask()
        }
    })

    pixTypeSelect.addEventListener('change', (event) => {
        pixTypeInput.value = ''
        pixTypeInput.type = 'text'
    })

    new MaskInput(pixTypeInput, {
        mask: () => pixTypes[pixTypeSelect.value].mask()
    })

    new MaskInput(pixAmountInput, {
        number: {
            locale: 'de',
            fraction: 2,
        },
        postProcess: (val) => val ? `R$ ${val}` : ''
    })

    const prepareFormData = (form) => {
        const formData = new FormData(form)
        const amount = formData.get('amount')
        if (amount) {
            formData.set('amount', parseFloat(
                amount.replace('R$ ', '').replace('.', '').replace(',', '.')
            ))
        }
        return formData
    }

    const getQRCode = async (params) => {
        const res = await fetch(`../src/qrcode.php?${params}`)
        return await res.json()
    }

    const openQRModal = (data) => {
        qrImage.src = data.qrcode
        qrModal.classList.add('is-active')
        qrCopy.addEventListener('click', () => {
            navigator.clipboard.writeText(data.code);
            qrCopy.querySelector('i').className = 'bx bx-check-double mr-1'
            qrCopy.querySelector('span').textContent = 'Texto copiado!'
            setTimeout(() => {
                qrCopy.querySelector('i').className = 'bx bx-copy mr-1'
                qrCopy.querySelector('span').textContent = 'Copiar Código Pix!'
            }, 1000)
        })
    }

    pixForm.addEventListener('submit', async (event) => {
        event.preventDefault()
        const submitBtn = event.target.querySelector('[type="submit"]')
        submitBtn.classList.add('is-loading')
        const formData = prepareFormData(event.target)
        const params = new URLSearchParams(formData)
        const data = await getQRCode(params)
        openQRModal(data)
        submitBtn.classList.remove('is-loading')
    })

</script>

<style>
    #app {
        height: 100vh;
    }

    #qr-image {
        border-radius: .5rem;
        object-fit: cover;
        max-width: 250px;
        max-height: 250px;
    }
</style>