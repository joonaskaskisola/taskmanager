import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';
import ModalViewTasks from '../modals/view-tasks.jsx';

export default class CustomerView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        if (this.props.loading && !this.props.row) {
            return <div></div>
        }

        if (this.props.row) {
            return <div className="ui segment">
                <div>
                    <ModalViewTasks customerId={this.props.row.id}/>
                </div>

                <Divider horizontal>Customer #{this.props.row.id}</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">Shipping Information</h4>

                    <div className="two fields">
                        <TextField e={this.props.e} name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                        <TextField e={this.props.e} name="name2" label="Name 2" value={this.props.row.name2} handleChange={this.props.handleChange} />
                    </div>

                    <div className="two fields">
                        <TextField e={this.props.e} name="businessId" label="BusinessId" value={this.props.row.businessId} handleChange={this.props.handleChange} />
                        <TextField e={this.props.e} name="contactPerson" label="Contact person" value={this.props.row.contactPerson} handleChange={this.props.handleChange} />
                    </div>

                    <h4 className="ui dividing header">Customer information</h4>

                    <div className="two fields">
                        <TextField e={this.props.e} name="streetAddress" label="Street address" value={this.props.row.streetAddress} handleChange={this.props.handleChange} />
                        <SelectField e={this.props.e} name="country" label="Country" options={this.props.countries} value={this.props.row.country} handleChange={this.props.handleSelectChange} />
                    </div>

                    <div className="two fields">
                        <TextField e={this.props.e} name="zipCode" label="Zipcode" value={this.props.row.zipCode} handleChange={this.props.handleChange} />
                        <TextField e={this.props.e} name="locality" label="Locality" value={this.props.row.locality} handleChange={this.props.handleChange} />
                    </div>

                    <NavigationButtons
                        footer={true}
                        closeRow={this.props.closeRow}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
            </div>
        }

        return <GridContainer
            search={true}
            fields={['name', 'businessId', 'streetAddress', 'country']}
            columns={['Name', 'Business id', 'Street address', 'Country']}
            rows={this.props.data}
            viewRow={this.props.viewRow}
            createNew={this.props.createNew}/>
    }
}
