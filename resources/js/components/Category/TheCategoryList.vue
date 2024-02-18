<template>
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<button class="btn btn-primary" @click="createCategory">
				Crear categoria
			</button>
		</div>
		<div class="card-body">
			<div class="table-responsive my-4 mx-2">
				<table class="table table-bordered" id="category_table">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody @click="handleAction"></tbody>
				</table>
			</div>
		</div>
	</div>
	<div v-if="load_modal">
		<category-modal :category_data="category" />
	</div>
</template>

<script>
	import {
		handlerErrors,
		successMessage,
		deleteMessage
	} from '@/helpers/Alerts.js'
	import HandlerModal from '@/helpers/HandlerModal.js'
	import CategoryModal from './CategoryModal.vue'
	import { ref, onMounted } from 'vue'
	export default {
		components: { CategoryModal },
		/*
		 * Setup se lanza en beforeCreated
		 */
		setup(/* props, context*/) {
			const categories = ref(null)
			const category = ref(null)
			const table = ref(null)
			const { openModal, closeModal, load_modal } = HandlerModal()

			onMounted(() => index())
			const index = () => mountedTable()

			const mountedTable = () => {
				table.value = $('#category_table').DataTable({
					destroy: true,
					processing: true,
					serverSide: true,
					scrollX: true,
					order: [[0, 'asc']],
					autoWidth: false,
					dom: 'Bfrtip',
					buttons: ['colvis', 'pageLength', 'excel', 'pdf', 'copy'],
					ajax: `/categories/get-all-dt`,
					columns: [
						{ data: 'name', name: 'name', orderable: true, searchable: true },
						{
							name: 'action',
							orderable: false,
							searchable: false,
							render: (data, type, row, meta) => {
								return `<div class="d-flex justify-content-center" data-role='actions'>
		            <button onclick='event.preventDefault();' data-id='${row.id}' role='edit' class="btn btn-warning btn-sm">
		              <i class='fas fa-pencil-alt' data-id='${row.id}' role='edit'></i>
								</button>
		            <button onclick='event.preventDefault();' data-id='${row.id}' role='delete' class="btn btn-danger btn-sm ms-1">
		            	<i class='fas fa-trash-alt' data-id='${row.id}' role='delete'></i>
								</button>
		          </div>`
							}
						}
					]
				})
			}

			const handleAction = event => {
				const button = event.target
				const category_id = button.getAttribute('data-id')
				if (button.getAttribute('role') == 'edit') {
					editCategory(category_id)
				} else if (button.getAttribute('role') == 'delete') {
					deleteCategory(category_id)
				}
			}

			const editCategory = async id => {
				try {
					const { data } = await axios.get(`/categories/${id}`)
					category.value = data.category
					await openModal('category_modal')
				} catch (error) {
					await handlerErrors(error)
				}
			}

			const createCategory = async () => {
				category.value = null
				await openModal('category_modal')
			}

			const deleteCategory = async id => {
				if (!(await deleteMessage())) return
				try {
					await axios.delete(`/categories/${id}`)
					await successMessage({ is_delete: true })
					reloadState()
				} catch (error) {
					await handlerErrors(error)
				}
			}

			const reloadState = () => {
				table.value.destroy()
				index()
			}

			// index()
			return {
				categories,
				category,
				load_modal,
				editCategory,
				deleteCategory,
				createCategory,
				closeModal,
				reloadState,
				handleAction
			}
		}
	}
</script>
