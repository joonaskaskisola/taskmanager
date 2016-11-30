import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
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
            return <div>
                <Divider horizontal>Task #{this.props.row.id}</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">General Information</h4>

                    <TextField readonly={true} name="name" label="Name" value={this.props.row.customerItem.name} handleChange={this.props.handleChange} />
                    <TextField name="description" label="Description" value={this.props.row.description} handleChange={this.props.handleChange} />

                    <SelectField name="customer" label="Customer" options={this.props.customers} value={this.props.row.customer.id} handleChange={this.props.handleSelectChange} />

                    <NavigationButtons
                        footer={true}
                        closeRow={this.props.closeRow}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
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
