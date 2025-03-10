<script setup lang="ts">

import { Mask } from 'maska'
import { ref } from 'vue'

const API = 'https://pixgenerator-api.vercel.app/'

interface PixForm {
  pix: string
  amount?: number
  txid?: string
  name?: string
  city?: string
  info?: string
}

interface PixType {
  id: string
  display: string
  mask?: string
  type: string
  placeholder: string
}

const pixTypes = ref<PixType[]>([
  {
    id: 'cpf',
    display: 'CPF',
    mask: '###.###.###-##',
    type: 'text',
    placeholder: '000.000.000-00'
  },
  {
    id: 'cnpj',
    display: 'CNPJ',
    mask: '##.###.###/####-##',
    type: 'text',
    placeholder: '00.000.000/0000-00'
  },
  {
    id: 'phone',
    display: 'Telefone',
    mask: '(##) #####-####',
    type: 'text',
    placeholder: '(00) 00000-0000'
  },
  {
    id: 'email',
    display: 'Email',
    type: 'email',
    placeholder: 'exemplo@mail.com',
  },
  {
    id: 'random',
    display: 'Aleatória',
    type: 'text',
    placeholder: 'Chave aleatória',
  },
])

const form = ref({} as PixForm)
const selectedType = ref(pixTypes.value[0])
const masks = {
  amount: {
    number: {
      locale: 'de',
      fraction: 2,
    },
    postProcess: (val: string) => val ? `R$ ${val}` : ''
  }
}

const loading = ref(false)

async function submit() {
  loading.value = true

  const mask = new Mask({ mask: selectedType.value.mask })

  form.value.pix = mask.unmasked(form.value.pix)

  if (selectedType.value.id === 'phone') {
    form.value.pix = '+55' + form.value.pix
  }

  if (form.value.amount) {
    form.value.amount = parseFloat(
      String(form.value.amount)
        .replace('R$ ', '')
        .replace('.', '')
        .replace(',', '.')
    )
  }

  const res = await fetch(API + '?' + new URLSearchParams(form.value))
  const json = await res.json()

  loading.value = false

  modal.value = {}

  openModal(json)
}

const modal = ref<any>({})

type QrResponse = {
  qr: string
  code: string
}

function openModal(data: QrResponse) {
  modal.value.qr = data.qr
  modal.value.code = data.code
}

function copyText() {
  navigator.clipboard.writeText(modal.value.code);
  modal.value.underDelay = true
  setTimeout(() => {
    modal.value.underDelay = false
  }, 1000)
}

const dark = ref(localStorage.getItem('dark') !== null)

if (dark.value) {
  document.documentElement.dataset.theme = 'dark'
} else {
  document.documentElement.dataset.theme = 'light'
}

function toggleTheme() {
  if (dark.value) {
    dark.value = false
    document.documentElement.dataset.theme = 'light'
    localStorage.removeItem('dark')
  } else {
    dark.value = true
    document.documentElement.dataset.theme = 'dark'
    localStorage.setItem('dark', 'dark')
  }
}
</script>

<template>
  <main id="app" class="is-flex is-justify-content-center is-align-items-center p-3">
    <div class="container is-max-tablet">
      <div class="content has-text-centered mb-0">
        <i class="box is-bordered bx bx-qr is-size-1 p-1 mb-0"></i>
        <h1 class="mt-3 mb-0">Gerador de QR Pix</h1>
        <p>Preencha os dados abaixo para gerar seu QRCode</p>
        <div class="is-flex is-justify-content-center is-align-items-center">
          <button @click="toggleTheme" class="button mr-4 p-1">
            <i class="bx is-size-5" :class="[dark ? 'bx-sun' : 'bx-moon']"></i>
          </button>
          <a class="m-0" target="_blank" href="https://github.com/victor-renan/pixgenerator">
            <i class="bx bxl-github"></i>
            Github
          </a>
        </div>
      </div>
      <div>
        <form @submit.prevent="submit" class="p-4">
          <div class="columns">
            <div class="column is-full">
              <div class="field">
                <label class="label">Chave Pix <strong class="has-text-danger">*</strong></label>
                <div class="field has-addons">
                  <div class="control has-icons-left is-expanded">
                    <input class="input" v-maska="selectedType.mask" v-model="form.pix" :type="selectedType.type"
                      :placeholder="selectedType.placeholder" required>
                    <span class="icon is-small is-left">
                      <i class="bx bx-key"></i>
                    </span>
                  </div>
                  <div class="control">
                    <div class="select">
                      <select @change="form.pix = ''" v-model="selectedType">
                        <option v-for="item in pixTypes" :key="item.id" :value="item">
                          {{ item.display }}
                        </option>
                      </select>
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
                  <input v-maska="masks.amount" v-model="form.amount" class="input" type="text" placeholder="R$ 0,00">
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
                  <input v-model="form.txid" class="input" type="text" placeholder="Palavra de identificação">
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
                  <input v-model="form.name" class="input" type="text" placeholder="Digite seu nome">
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
                  <input v-model="form.city" class="input" type="text" placeholder="Digite o nome de sua cidade">
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
                  <input v-model="form.info" class="input" type="text"
                    placeholder="Escreva uma mensagem em poucas palavras">
                  <span class="icon is-small is-left">
                    <i class="bx bx-message"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div>
            <button type="submit" class="button is-primary is-fullwidth" :class="[loading && 'is-loading']">
              <i class="bx bx-qr mr-1"></i>
              Gerar QR
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="modal" id="modal_qr" :class="[modal.qr && 'is-active']">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head is-shadowless">
          <p class="modal-card-title is-size-6 has-text-weight-bold">QR Code / Copia e Cola</p>
          <button class="delete" @click="() => modal = {}" aria-label="close"></button>
        </header>
        <section class="modal-card-body is-align-items-center">
          <div class="is-flex is-flex-direction-column is-align-items-center">
            <img id="qr-image" alt="QRCode" class="mb-3 mx-auto" :src="modal.qr">
          </div>
        </section>
        <footer class="modal-card-foot">
          <div class="buttons is-flex">
            <button v-if="!modal.underDelay" @click="copyText" id="qr-copy-code" class="button is-primary">
              <i class="bx mr-1 bx-copy"></i>
              <span>Copiar Código Pix</span>
            </button>
            <span v-else class="is-flex is-align-items-center p-1">
              <i class="has-text-primary bx bx-check is-size-3 me-1"></i>
              Código Copiado!
            </span>
          </div>
        </footer>
      </div>
    </div>
  </main>
</template>

<style scoped>
#app {
  min-height: 100vh;
}

#qr-image {
  border-radius: .5rem;
  object-fit: cover;
  max-width: 250px;
  max-height: 250px;
}
</style>
