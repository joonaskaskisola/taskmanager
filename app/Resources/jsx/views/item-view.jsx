import React from 'react';
import { render } from 'react-dom';
import TextField from '../components/text.jsx';
import SelectField from '../components/select.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class ItemView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <Divider horizontal>Item #{this.props.row.id}</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">General Information</h4>

                    <div className="two fields">
                        <TextField name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                        <TextField name="price" label="Price" value={this.props.row.price} handleChange={this.props.handleChange} />
                    </div>

                    <div className="two fields">
                        <SelectField name="category" label="Category" options={this.props.categories} value={this.props.row.category.id} handleChange={this.props.handleSelectChange} />
                        <SelectField name="unit" label="Unit" options={this.props.units} value={this.props.row.unit.id} handleChange={this.props.handleSelectChange} />
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
            fields={['name', 'price']}
            columns={['Name', 'Price']}
            rows={this.props.data}
            viewRow={this.props.viewRow}
            createNew={this.props.createNew}/>
    }
}
