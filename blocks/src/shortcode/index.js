( function( wp ) {
	const {registerBlockType} = wp.blocks;
	const {Component, Fragment} = wp.element;
	const {SelectControl, Spinner} = wp.components;

	registerBlockType( 'egoi-for-wp/shortcode', {
		title: 'E-goi - Formulários',
		description: 'Shortcode dos formularios criados no plugin do E-goi',
		category: 'embed',
		icon: <svg viewBox="0 0 372 271" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M249.006 3.05893L123.184 0.355957L118.309 270.653L164.883 224.495L257.823 214.203L249.006 3.05893Z" fill="#00AEDA"/><path d="M103.057 2.53906L110.111 217.737C81.2745 223.039 19.3487 213.995 2.85594 135.193C-13.6368 56.3905 62.7071 13.8707 103.057 2.53906Z" fill="#00AEDA"/><path d="M265.07 40.5884L267.456 210.564C296.292 215.866 357.285 211.083 370.251 149.435C383.217 87.7864 305.524 51.1923 265.07 40.5884Z" fill="#00AEDA"/></svg>,
		keywords: ['egoi', 'e-goi', 'shortcode'],

		attributes: {
			form: {
				type: 'string',
				default: null,
			}
		},

		edit: class extends Component {
			constructor(props) {
				super(...arguments);
				this.props = props;
				this.state = {
					forms: null,
					loading: true,
				};
			}

			componentDidMount() {
				// ajax call
				jQuery.get(ajax_url, {action: 'efwp_get_egoi_forms'}).done((data) => {
					let json = JSON.parse(data)

					if (json != null) {
						var arr = [{label: 'Selecione', value: ''}];
						for (var k in json) {
							let obj = json[k];
							arr.push({label: obj.title + ' (' + obj.id + ')', value: obj.shortcode});
						}
						this.setState( () => ({forms: arr}) );
					}

					this.setState( () => ({loading: false}) );
				});
			}

			render() {
				const { attributes: {form}, setAttributes, isSelected } = this.props;
				const divStyle = {
					display: 'flex',
					alignItems: 'center',
					justifyContent: 'center',
					height: '65px',
					backgroundColor: 'rgba(139,139,150,.1)',
				};

				let forms = this.state.forms;
				let loading = this.state.loading;

				return (
					<Fragment>
						{forms == null && loading && (
							<div style={divStyle}>
								<Spinner />
							</div>
						)}
						{forms != null && !loading && (
							<SelectControl
								label="Selecione um formulários"
								value={ form }
								options={ forms }
								onChange={ ( form ) => { setAttributes({ form: form }) } }
							/>
						)}
						{forms == null && !loading && (
							<p>Ainda não tem formulários criados no plugin do E-goi.</p>
						)}
					</Fragment>
				);
			}
		},
		
		save: function(props) {
			const {attributes: {form} } = props;
			return (
				<Fragment>{form}</Fragment>
			);
		}
	} );
} )(
	window.wp
);
