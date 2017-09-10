import React from 'react';
import AbstractView from './abstract-view.jsx';

import TextField from '../components/text.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import {Divider, Menu, Input, Segment} from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class ItemView extends AbstractView {
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
						app='item'
						header={true}
						nextRow={this.props.nextRow}
						previousRow={this.props.previousRow}
						showPrev={this.props.showPrev}
						showNext={this.props.showNext}/>
				</Menu>

				<Segment attached='bottom'>
					<div className={'ui form ' + (this.props.loading ? 'loading' : '')}>

						<div className='two fields'>
							<TextField name='name' label='Name' value={this.props.row.name}
							           handleChange={this.props.handleChange}/>
							<TextField name='price' label='Price' value={this.props.row.price}
							           handleChange={this.props.handleChange}/>
						</div>

						<div className='two fields'>
							<SelectField name='category' label='Category' options={this.props.categories} value={
								this.props.row.category.id
							} handleChange={this.props.handleSelectChange}/>
							<SelectField name='unit' label='Unit' options={this.props.units} value={
								this.props.row.unit.id
							} handleChange={this.props.handleSelectChange}/>
						</div>

					</div>
				</Segment>

				<Menu attached='bottom' tabular>
					<NavigationButtons
						app='item'
						footer={true}
						closeRow={this.props.closeRow}
						handleSubmit={this.props.handleSubmit}/>
				</Menu>
			</div>
		}

		return <GridContainer
			app='item'
			search={true}
			fields={['name', 'price']}
			columns={['Name', 'Price']}
			rows={this.props.data}
			viewRow={this.props.viewRow}
			createNew={this.props.createNew}/>
	}
}
