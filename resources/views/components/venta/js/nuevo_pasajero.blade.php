@push('scripts')
<script>
class nuevoPasajero extends HTMLElement{
	constructor(){
		super();
		this.idx = null;
	}

	static get observedAttributes(){
		return ['idx'];
	}

	attributeChangeCallback(idAttr, oldValue, newValue){
		this.idx = newValue;
	}

	connectedCallback(){
		this.innerHTML = `
		<div class="row no-gutters">
		<div class="col-12 col-md-3">
		<input type="text" class="form-control form-control-sm" placholder="Nombre pasajero ${this.idx}">
		</div>
		</div>
		`;
	}
}

window.customElements.define('nuevo-pasajero', nuevoPasajero);
</script>
@endpush