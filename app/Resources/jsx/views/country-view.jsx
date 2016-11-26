import React from 'react';
import { render } from 'react-dom';
import SelectField from '../components/select.jsx';
import TextField from '../components/text.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class CountryView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <Divider horizontal>Country #{this.props.row.id}</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">General Information</h4>

                    <div className="field">
                        <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                    </div>

                    <div className="two fields">
                        <SelectField search={true} options={this.props.flags} pos="left" name="code" label="Code" value={this.props.row.code} handleChange={this.props.handleSelectChange} />
                        <TextField pos="right" name="lang_code" label="Lang code" value={this.props.row.lang_code} handleChange={this.props.handleChange} />
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
            fields={['name', 'code', 'lang_code']}
            columns={['Name', 'Code', 'Lang code']}
            rows={this.props.data}
            viewRow={this.props.viewRow}
            createNew={this.props.createNew}/>
    }
}
