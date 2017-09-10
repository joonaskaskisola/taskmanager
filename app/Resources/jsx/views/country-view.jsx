import React from 'react';
import AbstractView from './abstract-view.jsx';

import SelectField from '../components/select.jsx';
import TextField from '../components/text.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import {Divider, Menu, Item, Segment} from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class CountryView extends AbstractView {
	constructor(props, context) {
		super(props, context);
	}

	render() {
		if (this.props.loading) {
			return <div></div>
		}

		if (this.props.row) {
			return <div className='ui segment'>
				<Menu attached='top' tabular>
					<Menu.Item name='Details' active={true}/>
					<NavigationButtons
						app='country'
						header={true}
						nextRow={this.props.nextRow}
						previousRow={this.props.previousRow}
						showPrev={this.props.showPrev}
						showNext={this.props.showNext}/>
				</Menu>

				<Segment attached='bottom'>
					<div className={'ui form ' + (this.props.loading ? 'loading' : '')}>
						<div className='field'>
							<TextField pos='left' name='name' label='Name' value={this.props.row.name}
							           handleChange={this.props.handleChange}/>
						</div>

						<div className='two fields'>
							<SelectField search={true} options={this.props.flags} pos='left' name='code' label='Code'
							             value={this.props.row.code} handleChange={this.props.handleSelectChange}/>
							<TextField pos='right' name='langCode' label='Lang code' value={this.props.row.langCode}
							           handleChange={this.props.handleChange}/>
						</div>
					</div>
				</Segment>

				<Menu attached='bottom' tabular>
					<NavigationButtons
						app='country'
						footer={true}
						closeRow={this.props.closeRow}
						handleSubmit={this.props.handleSubmit}/>
				</Menu>
			</div>
		}

		return <GridContainer
			app='country'
			search={true}
			fields={['name', 'code', 'langCode']}
			columns={['Name', 'Code', 'Lang code']}
			rows={this.props.data}
			viewRow={this.props.viewRow}
			createNew={this.props.createNew}/>
	}
}
