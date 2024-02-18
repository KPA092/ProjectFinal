<template>
	<div
		class="modal fade"
		id="product_modal"
		data-bs-backdrop="static"
		data-bs-keyboard="false"
		tabindex="-1"
		aria-labelledby="staticBackdropLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						{{ is_create ? 'Crear' : 'Editar' }} Producto
					</h5>
					<button
						type="button"
						class="btn-close"
						data-bs-dismiss="modal"
						aria-label="Close"
					></button>
				</div>

				<backend-error :errors="back_errors" />

				<!-- Formulario -->
				<Form @submit="saveProduct" :validation-schema="schema" ref="form">
					<div class="modal-body">
						<section class="row">
							<!-- Image -->
							<div class="col-12 d-flex justify-content-center mt-1">
								<img
									:src="image_preview"
									alt="Imagen Producto"
									class="img-thumbnail"
									width="170"
									height="170"
								/>
							</div>

							<!-- Load Image -->
							<div class="col-12 mt-1">
								<label for="file" class="form-label">Imagen</label>
								<input
									type="file"
									:class="`form-control ${
										back_errors['file'] ? 'is-invalid' : ''
									}`"
									id="file"
									accept="image/*"
									@change="previewImage"
								/>
								<span class="invalid-feedback" v-if="back_errors['file']">
									{{ back_errors['file'] }}
								</span>
							</div>

							<!-- Title -->
							<div class="col-12">
								<label for="name">Nombre</label>
								<Field
									name="name"
									v-slot="{ errorMessage, field }"
									v-model="product.name"
								>
									<input
										type="text"
										id="name"
										v-model="product.name"
										:class="`form-control ${
											errorMessage || back_errors['name'] ? 'is-invalid' : ''
										}`"
										v-bind="field"
									/>
									<span class="invalid-feedback">{{ errorMessage }}</span>
									<span class="invalid-feedback">{{
										back_errors['name']
									}}</span>
								</Field>
							</div>

							<!-- Stock -->
							<div class="col-12 mt-2">
								<Field
									name="stock"
									v-slot="{ errorMessage, field }"
									v-model="product.stock"
								>
									<label for="stock">Cantidad</label>
									<input
										type="number"
										id="stock"
										v-model="product.stock"
										:class="`form-control ${errorMessage ? 'is-invalid' : ''}`"
										v-bind="field"
									/>
									<span class="invalid-feedback">{{ errorMessage }}</span>
								</Field>
							</div>

							<!-- Description -->
							<div class="col-12 mt-2">
								<Field
									name="description"
									v-slot="{ errorMessage, field }"
									v-model="product.description"
								>
									<label for="description">Descripcion</label>
									<textarea
										v-model="product.description"
										:class="`form-control ${errorMessage ? 'is-invalid' : ''}`"
										id="description"
										rows="3"
										v-bind="field"
									></textarea>
									<span class="invalid-feedback">{{ errorMessage }}</span>
								</Field>
							</div>

							<!-- Precio -->
							<div class="col-12 mt-2">
								<Field
									name="price"
									v-slot="{ errorMessage, field }"
									v-model="product.price"
								>
									<label for="price">Precio</label>
									<input
										type="number"
										id="price"
										v-model="product.price"
										class="form-control"
										v-bind="field"
									/>
									<span class="invalid-feedback">{{ errorMessage }}</span>
								</Field>
							</div>

							<!-- Category -->
							<div class="col-12 mt-2" v-if="load_category">
								<Field
									name="category"
									v-slot="{ errorMessage, field, valid }"
									v-model="category"
								>
									<label for="category">Categoria</label>

									<v-select
										id="category"
										:options="categories_data"
										v-model="category"
										:reduce="category => category.id"
										v-bind="field"
										label="name"
										placeholder="Selecciona categoria"
										:clearable="false"
										:class="`${errorMessage ? 'is-invalid' : ''}`"
									>
									</v-select>
									<span class="invalid-feedback" v-if="!valid">{{
										errorMessage
									}}</span>
								</Field>
							</div>
						</section>
					</div>

					<!-- Buttons -->
					<div class="modal-footer">
						<button
							type="button"
							class="btn btn-secondary"
							data-bs-dismiss="modal"
						>
							Close
						</button>
						<button type="sumbit" class="btn btn-primary">Almacenar</button>
					</div>
				</Form>
			</div>
		</div>
	</div>
</template>

<script>
	import { Field, Form } from 'vee-validate'
	import * as yup from 'yup'
	import { successMessage, handlerErrors } from '@/helpers/Alerts.js'

	export default {
		props: ['product_data'],
		components: { Field, Form },
		watch: {
			product_data(new_value) {
				this.product = { ...new_value }
				if (!this.product.id) return
				this.is_create = false
				this.category = this.product.category_id
				this.image_preview = this.product.file.route
			}
		},
		computed: {
			schema() {
				return yup.object({
					name: yup.string().required(),
					stock: yup.number().required().positive().integer(),
					description: yup.string(),
					price: yup.number().required().positive(),
					category: yup.string().required()
				})
			}
		},
		data() {
			return {
				is_create: true,
				product: {},
				category: null,
				categories_data: [],
				load_category: false,
				back_errors: {},
				file: null,
				image_preview: '/storage/images/products/default.png'
			}
		},
		created() {
			this.index()
		},

		methods: {
			index() {
				this.getCategories()
			},
			previewImage(envent) {
				this.file = envent.target.files[0]
				this.image_preview = URL.createObjectURL(this.file)
			},
			async saveProduct() {
				try {
					this.product.category_id = this.category
					const product = this.createFormData(this.product)
					if (this.is_create) await axios.post('/products/store', product)
					else await axios.post(`/products/update/${this.product.id}`, product)
					await successMessage({ reload: true })
				} catch (error) {
					this.back_errors = await handlerErrors(error)
				}
			},
			createFormData(data) {
				const form_data = new FormData()
				if (this.file) form_data.append('file', this.file, this.file.name)
				for (const prop in data) {
					form_data.append(prop, data[prop])
				}
				return form_data
			},

			async getCategories() {
				try {
					const {
						data: { categories }
					} = await axios.get('/categories/get-all')
					this.categories_data = categories
					this.load_category = true
				} catch (error) {
					await handlerErrors(error)
				}
			},
			reset() {
				this.is_create = true
				this.product = {}
				this.category = null
				this.$parent.product = {}
				this.back_errors = {}
				this.file = null
				this.image_preview = '/storage/images/books/default.png'
				document.getElementById('file').value = ''
				setTimeout(() => this.$refs.form.resetForm(), 100)
			}
		}
	}
</script>

<style lang="scss" scoped></style>
