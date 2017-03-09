import React from 'react';
import TextField from '../components/text.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider, Menu, Input, Segment } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class TaskView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div className="ui segment">
                <Menu attached='top' tabular>
                    <Menu.Item name='Details' active={true}/>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>
                </Menu>

                <Segment attached='bottom'>
                    <div className={"ui form " + (this.props.loading ? "loading" : "")}>

                        <TextField readonly={true} name="name" label="Name" value={this.props.row.customerItem.name} handleChange={this.props.handleChange} />
                        <TextField name="description" label="Description" value={this.props.row.description} handleChange={this.props.handleChange} />

                        <SelectField name="customer" label="Customer" options={this.props.customers} value={this.props.row.customer.id} handleChange={this.props.handleSelectChange} />

                    </div>
                </Segment>

                <Menu attached='bottom' tabular>
                    <NavigationButtons
                        footer={true}
                        closeRow={this.props.closeRow}
                        handleSubmit={this.props.handleSubmit}/>
                </Menu>
            </div>
        }

        return <GridContainer
            search={true}
            fields={['customerItem.name', 'customer.name']}
            columns={['Name', 'Customer']}
            rows={this.props.data}
            viewRow={this.props.viewRow}
            createNew={this.props.createNew}/>
    }
}
